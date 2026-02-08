<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JournalExport;

class JournalController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        $from = $request->get('from_date', Carbon::today()->toDateString());
        $to   = $request->get('to_date', Carbon::today()->toDateString());

        $journals = Journal::with([
                'details.account'
            ])
            ->whereBetween('date', [$from, $to])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        return view('journals.index', [
            'journals' => $journals,
            'from'     => $from,
            'to'       => $to,
        ]);
    }

    /* =========================
     * EXPORT EXCEL
     * ========================= */
    public function export(Request $request)
    {
        $from = $request->get('from_date', Carbon::today()->toDateString());
        $to   = $request->get('to_date', Carbon::today()->toDateString());

        return Excel::download(
            new JournalExport($from, $to),
            "jurnal-{$from}-sd-{$to}.xlsx"
        );
    }
}
