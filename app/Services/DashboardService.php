<?php

namespace App\Services;

use App\Model\Product;
use App\Model\Transaction;
use App\Model\TransactionDetail;
use Carbon\Carbon;
use DB;

/**
 * Figures of the admin dashboard (GET admin/home).
 */
class DashboardService
{
    public function summary()
    {
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

        return $data;
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
}
