<?php

namespace App\Exports;

use App\Models\Journal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JournalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection()
    {
        return Journal::with('details.account')
            ->whereBetween('date', [$this->from, $this->to])
            ->orderBy('date')
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Deskripsi',
            'Akun',
            'Debit',
            'Kredit',
        ];
    }

    public function map($journal): array
    {
        $rows = [];

        foreach ($journal->details as $detail) {
            $rows[] = [
                $journal->date->format('Y-m-d'),
                $journal->description,
                $detail->account->code . ' - ' . $detail->account->name,
                $detail->debit,
                $detail->credit,
            ];
        }

        return $rows;
    }
}
