<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\Customer;
use App\Model\TransactionDetail;
use Carbon\Carbon;
use DB;
use PDF;
use App\Model\Category;
use App\Model\Product;
use App\User;

class HomeController extends Controller
{
    public function index() {
        $data = [];
        $thisMonth = Carbon::now()->format('Y-m');
        $lastMonth = Carbon::now()->subMonth(1)->format('Y-m');

        $data['transaksi_bulan_ini'] = $this->countForMonth($thisMonth);
        $data['transaksi_bulan_lalu'] = $this->countForMonth($lastMonth);
        $data['peningkatan_transaksi'] = $this->growthPercentage($data['transaksi_bulan_ini'], $data['transaksi_bulan_lalu'], '-');
        $data['average_price'] = Product::selectRaw("AVG(price+ (price * ppn/100) ) as average")->first()->average;
        $data['product_sold'] = TransactionDetail::selectRaw('SUM(quantity) as quantity')->first()->quantity;
        $data['product_sold_lastmo'] = TransactionDetail::selectRaw('SUM(quantity) as quantity')->where('created_at','LIKE',"%$lastMonth%")->first()->quantity;
        $data['pendapatan_bulan_ini'] = $this->sumForMonth($thisMonth);
        $data['pendapatan_bulan_lalu'] = $this->sumForMonth($lastMonth);
        $data['peningkatan_pendapatan'] = $this->growthPercentage($data['pendapatan_bulan_ini'], $data['pendapatan_bulan_lalu'], '+');

        return response()->json($data);
    }

    /**
     * Number of orders created in the month 'Y-m'.
     */
    private function countForMonth($month)
    {
        return Transaction::where('created_at', 'LIKE', '%'.$month.'%')->count();
    }

    /**
     * "Pendapatan" of the month 'Y-m': the sum of the PPN of the order details (D-19, kept).
     */
    private function sumForMonth($month)
    {
        return DB::table('order_details')
            ->select(DB::raw("SUM(ppn) as fee"))
            ->where('order_details.created_at', 'LIKE', '%'.$month.'%')
            ->first()->fee;
    }

    /**
     * Growth from last month as "+n%" or "-n%".
     *
     * D-03 (kept): the divisor ($previous !== 0 ?: 1) is always 1, so n is the
     * difference x 100. A difference of 0 gets $zeroSign: the order figure
     * prints "-0%", the revenue figure prints "+0%".
     */
    private function growthPercentage($current, $previous, $zeroSign)
    {
        $diff = $current - $previous;
        $sign = $diff > 0 ? '+' : ($diff < 0 ? '-' : $zeroSign);

        return $sign.((abs($diff)/($previous!==0?:1))*100)."%";
    }
    public function report() {
        $data = [];
        $data['total']['transaction'] = Transaction::count();
        $data['total']['transaction_thismo'] = Transaction::whereMonth('created_at', Carbon::now()->format('m'))->count();
        $data['total']['category'] = Category::count();
        $data['total']['product'] = Product::count();
        $data['total']['product_sold'] = TransactionDetail::count();
        $data['total']['product_sold_thismo'] = TransactionDetail::whereMonth('created_at', Carbon::now()->format('m'))->count();
        $data['total']['customer'] = Customer::count();

        $data['transaction']['chart'] = DB::table('orders')->select(DB::raw("COUNT(*) as a, DATE(created_at) as y"))->where('created_at', '>=', Carbon::now()->subDays(7)->format('Y-m-d'))->groupBy(DB::raw('DATE(created_at)'))->get();

        return response()->json($data);
    }

    public function pdf_transaction(Request $r) {
        return $this->renderPdf('pdf.transaction', ['transactions' => Transaction::all()], 'transaction.pdf');
    }

    public function pdf_users(Request $r) {
        return $this->renderPdf('pdf.users', ['users' => User::all()], 'users.pdf');
    }

    public function pdf_products(Request $r) {
        return $this->renderPdf('pdf.products', ['products' => Product::all()], 'products.pdf', ['a4', 'landscape']);
    }

    /**
     * Saves the view as public/pdf/<file> and answers with the file name.
     * Without $paper, dompdf uses its default paper.
     */
    private function renderPdf($view, array $data, $file, array $paper = null)
    {
        $pdf = PDF::loadview($view, $data);
        if ($paper) {
            $pdf->setPaper(...$paper);
        }
        $pdf->save('pdf/'.$file);

        return response()->json(['status' => true, 'filename' => $file]);
    }
}
