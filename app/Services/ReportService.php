<?php

namespace App\Services;

use App\Model\Category;
use App\Model\Customer;
use App\Model\Product;
use App\Model\Transaction;
use App\Model\TransactionDetail;
use App\User;
use Carbon\Carbon;
use DB;
use PDF;

/**
 * Data of the report page (GET report) and the PDF reports.
 */
class ReportService
{
    /**
     * D-11 (kept): "this month" is whereMonth without whereYear.
     */
    public function summary()
    {
        $data = [];
        $data['total']['transaction'] = Transaction::count();
        $data['total']['transaction_thismo'] = Transaction::whereMonth('created_at', Carbon::now()->format('m'))->count();
        $data['total']['category'] = Category::count();
        $data['total']['product'] = Product::count();
        $data['total']['product_sold'] = TransactionDetail::count();
        $data['total']['product_sold_thismo'] = TransactionDetail::whereMonth('created_at', Carbon::now()->format('m'))->count();
        $data['total']['customer'] = Customer::count();

        $data['transaction']['chart'] = DB::table('orders')->select(DB::raw("COUNT(*) as a, DATE(created_at) as y"))->where('created_at', '>=', Carbon::now()->subDays(7)->format('Y-m-d'))->groupBy(DB::raw('DATE(created_at)'))->get();

        return $data;
    }

    /** @return string  the file name in public/pdf */
    public function saveTransactionsPdf()
    {
        return $this->savePdf('pdf.transaction', ['transactions' => Transaction::all()], 'transaction.pdf');
    }

    /** @return string  the file name in public/pdf */
    public function saveUsersPdf()
    {
        return $this->savePdf('pdf.users', ['users' => User::all()], 'users.pdf');
    }

    /** @return string  the file name in public/pdf */
    public function saveProductsPdf()
    {
        return $this->savePdf('pdf.products', ['products' => Product::all()], 'products.pdf', ['a4', 'landscape']);
    }

    /**
     * Saves the view as public/pdf/<file>. Without $paper, dompdf uses its default paper.
     */
    private function savePdf($view, array $data, $file, array $paper = null)
    {
        $pdf = PDF::loadview($view, $data);
        if ($paper) {
            $pdf->setPaper(...$paper);
        }
        $pdf->save('pdf/'.$file);

        return $file;
    }
}
