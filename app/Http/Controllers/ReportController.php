<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use App\Models\Purchase;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start_date = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $end_date = date('Y-m-d');

        if ($request->has('start_date') && $request->start_date != "" && $request->has('end_date') && $request->end_date) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
        }

        return view('admin.report.index', compact('start_date', 'end_date'));
    }

    public function getData($start, $end)
    {
        $no = 1;
        $data = array();


        while (strtotime($start) <= strtotime($end)) {
            $date = $start;
            $start = date('Y-m-d', strtotime("+1 day", strtotime($start)));

            $total_deposit = Purchase::where('created_at', 'LIKE', "%$date%")->sum('pay');
            $total_rubbish = Purchase::where('created_at', 'LIKE', "%$date%")->sum('total_item');
            $total_expendature = Expenditure::where('created_at', 'LIKE', "%$date%")->sum('nominal');


            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['date'] = tanggal_indonesia($date, false);
            $row['deposit_rubbish'] = format_uang($total_deposit);
            $row['total_rubbish'] = $total_rubbish;
            $row['expendature'] = format_uang($total_expendature);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'date' => '',
            'deposit_rubbish' => '',
            'total_rubbish' => '',
            'expendature' => '',
        ];

        return $data;
    }

    public function data($start, $end)
    {
        $data = $this->getData($start, $end);

        return datatables()
            ->of($data)
            ->make(true);
    }

}
