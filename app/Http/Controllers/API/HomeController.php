<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\ReportService;

class HomeController extends Controller
{
    private $dashboard;

    private $reports;

    public function __construct(DashboardService $dashboard, ReportService $reports)
    {
        $this->dashboard = $dashboard;
        $this->reports = $reports;
    }

    public function index() {
        return response()->json($this->dashboard->summary());
    }

    public function report() {
        return response()->json($this->reports->summary());
    }

    public function pdf_transaction(Request $r) {
        return $this->pdfSaved($this->reports->saveTransactionsPdf());
    }

    public function pdf_users(Request $r) {
        return $this->pdfSaved($this->reports->saveUsersPdf());
    }

    public function pdf_products(Request $r) {
        return $this->pdfSaved($this->reports->saveProductsPdf());
    }

    private function pdfSaved($filename)
    {
        return response()->json(['status' => true, 'filename' => $filename]);
    }
}
