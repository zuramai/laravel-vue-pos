<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Imports laravel_pos.sql into the test database once per PHPUnit process.
 *
 * Every test then runs inside a transaction (DatabaseTransactions), so each
 * test starts from the same seed data as the demo application.
 */
trait ImportsSeedDatabase
{
    /** @var bool */
    protected static $seedImported = false;

    protected function importSeedDatabaseOnce()
    {
        if (static::$seedImported) {
            return;
        }

        $database = DB::connection()->getDatabaseName();
        if ($database !== 'pos_test') {
            throw new RuntimeException("Refusing to import the seed into [{$database}]. Tests must use pos_test.");
        }

        $sql = file_get_contents(static::seedSqlPath());

        DB::unprepared('SET FOREIGN_KEY_CHECKS = 0');
        foreach (DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"') as $row) {
            $table = array_values((array) $row)[0];
            DB::unprepared("DROP TABLE IF EXISTS `{$table}`");
        }
        DB::unprepared('SET FOREIGN_KEY_CHECKS = 1');

        DB::unprepared($sql);

        // The dump runs "SET AUTOCOMMIT = 0" and changes session variables.
        // A new connection gives every test the default session state.
        DB::purge();
        DB::reconnect();

        static::$seedImported = true;
    }

    public static function seedSqlPath()
    {
        return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'laravel_pos.sql';
    }
}
