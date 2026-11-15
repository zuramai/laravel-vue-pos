<?php

namespace Tests;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\Concerns\ImportsSeedDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Base class for the characterization tests of the HTTP API.
 *
 * - The seed dump is imported once. Each test runs in a rolled back transaction.
 * - The clock is frozen, so date based queries give the same result every run.
 * - The working directory is public/, the same as "php artisan serve".
 *   Some controllers write to relative paths (images/products, pdf/*.pdf).
 * - Files written by uploads and PDF reports are removed or restored after each test.
 */
abstract class ApiTestCase extends TestCase
{
    use ImportsSeedDatabase;
    use DatabaseTransactions;

    /** Default frozen time. No seed row has this date. */
    const NOW = '2026-03-15 10:00:00';

    /** Prefix of every file name uploaded by a test. tearDown deletes these files. */
    const UPLOAD_PREFIX = 'chartest_';

    /** @var string */
    private $previousCwd;

    /** @var array<string,string> */
    private $pdfBackup = [];

    /** @var string|null */
    private static $seedSql;

    protected function setUpTraits()
    {
        $this->importSeedDatabaseOnce();

        return parent::setUpTraits();
    }

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse(static::NOW));

        $this->previousCwd = getcwd();
        chdir(public_path());

        foreach (glob(public_path('pdf/*.pdf')) as $file) {
            $this->pdfBackup[$file] = file_get_contents($file);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->pdfBackup as $file => $bytes) {
            file_put_contents($file, $bytes);
        }
        foreach (glob(public_path('images/*/'.static::UPLOAD_PREFIX.'*')) as $file) {
            @unlink($file);
        }

        chdir($this->previousCwd);
        Carbon::setTestNow();

        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // Clock
    // ------------------------------------------------------------------

    protected function freezeTime($datetime)
    {
        Carbon::setTestNow(Carbon::parse($datetime));
    }

    // ------------------------------------------------------------------
    // Authentication and requests
    // ------------------------------------------------------------------

    protected function userByUsername($username)
    {
        return User::where('username', $username)->firstOrFail();
    }

    protected function tokenFor($username)
    {
        return JWTAuth::fromUser($this->userByUsername($username));
    }

    /**
     * The JWT guard caches the user between requests in one test.
     * Forget the guards so that every request authenticates again.
     */
    protected function forgetAuthGuards()
    {
        $manager = $this->app['auth'];
        (function () {
            $this->guards = [];
        })->call($manager);
        $this->app->forgetInstance('tymon.jwt');
        $this->app->forgetInstance('tymon.jwt.auth');
    }

    protected function headersFor($as)
    {
        $server = [
            'HTTP_ACCEPT' => 'application/json, text/plain, */*',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        ];
        if ($as !== null) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer '.$this->tokenFor($as);
        }

        return $server;
    }

    /**
     * Sends a request like the axios client of the UI does with query
     * parameters or FormData: every field arrives as a string.
     */
    protected function api($method, $uri, array $data = [], array $files = [], $as = 'admin')
    {
        $this->forgetAuthGuards();

        return $this->call($method, '/api/v1/'.ltrim($uri, '/'), $data, [], $files, $this->headersFor($as));
    }

    /**
     * Sends a JSON body, like axios.post(url, object) in the UI.
     */
    protected function apiJson($method, $uri, array $data = [], $as = 'admin')
    {
        $this->forgetAuthGuards();

        $server = $this->headersFor($as);
        $server['CONTENT_TYPE'] = 'application/json';

        return $this->call($method, '/api/v1/'.ltrim($uri, '/'), [], [], [], $server, json_encode($data));
    }

    protected function fakeImage($suffix = 'photo.jpg')
    {
        return UploadedFile::fake()->image(static::UPLOAD_PREFIX.$suffix, 10, 10);
    }

    // ------------------------------------------------------------------
    // Golden master snapshots
    // ------------------------------------------------------------------

    /**
     * Compares a response body with a stored golden master file.
     *
     * Missing snapshot files fail the test. Set SNAPSHOT_UPDATE=1 to write them.
     * Values that change between runs (new timestamps, new UUIDs, tokens) are
     * replaced with placeholders before the compare.
     */
    protected function assertMatchesSnapshot($name, $actual)
    {
        if ($actual instanceof \Illuminate\Foundation\Testing\TestResponse) {
            $actual = [
                'status' => $actual->getStatusCode(),
                'body' => json_decode($actual->getContent(), true),
            ];
        }

        $actual = $this->normalizeVolatile($actual);
        $encoded = json_encode($actual, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n";

        $class = (new \ReflectionClass($this))->getShortName();
        $file = __DIR__.'/__snapshots__/'.$class.'/'.$name.'.json';

        if (getenv('SNAPSHOT_UPDATE') === '1') {
            if (!is_dir(dirname($file))) {
                mkdir(dirname($file), 0777, true);
            }
            file_put_contents($file, $encoded);
        }

        $this->assertFileExists($file, "Snapshot [{$class}/{$name}] is missing. Run with SNAPSHOT_UPDATE=1 to create it.");
        $this->assertSame(file_get_contents($file), $encoded, "Response differs from snapshot [{$class}/{$name}].");
    }

    protected function normalizeVolatile($value, $key = null)
    {
        if (is_array($value)) {
            $result = [];
            foreach ($value as $k => $v) {
                $result[$k] = $this->normalizeVolatile($v, $k);
            }

            return $result;
        }

        if (!is_string($value)) {
            return $value;
        }

        if ($key === 'access_token') {
            return '<token>';
        }

        // Timestamps written during the test carry the frozen date.
        $today = Carbon::now()->format('Y-m-d');
        if (in_array($key, ['created_at', 'updated_at', 'deleted_at'], true) && Str::startsWith($value, $today)) {
            return '<now>';
        }

        // UUIDs generated during the test. Seed UUIDs stay visible.
        return preg_replace_callback(
            '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/i',
            function ($m) {
                return strpos(static::seedSql(), $m[0]) === false ? '<uuid>' : $m[0];
            },
            $value
        );
    }

    private static function seedSql()
    {
        if (self::$seedSql === null) {
            self::$seedSql = file_get_contents(static::seedSqlPath());
        }

        return self::$seedSql;
    }
}
