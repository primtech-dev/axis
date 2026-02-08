@extends('layouts.vertical', ['title' => 'Jurnal Umum'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Jurnal Umum',
        'subTitle' => 'Pencatatan transaksi akuntansi',
    ])

    {{-- FILTER --}}
    <form method="GET" class="card mb-3">
        <div class="card-body row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="from_date" value="{{ $from }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="to_date" value="{{ $to }}" class="form-control">
            </div>

            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-primary">
                    <i class="ti ti-filter"></i> Filter
                </button>

                <a href="{{ route('journals.export', ['from_date' => $from, 'to_date' => $to]) }}"
                    class="btn btn-outline-success">
                    <i class="ti ti-file-spreadsheet"></i> Export Excel
                </a>
            </div>

        </div>
    </form>

    {{-- TABLE JURNAL --}}
    <div class="card">
        <div class="card-body table-responsive">

            @if ($journals->count() === 0)
                <div class="text-muted">Tidak ada data jurnal.</div>
            @else
                @foreach ($journals as $journal)
                    {{-- ================= HEADER JURNAL ================= --}}
                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $journal->date->format('Y-m-d') }}</strong>
                                <span class="text-muted ms-2">
                                    {{ $journal->reference_type }} #{{ $journal->reference_id }}
                                </span>
                            </div>

                            <div class="text-muted">
                                {{ $journal->description }}
                            </div>
                        </div>

                        {{-- ================= DETAIL JURNAL ================= --}}
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end" width="180">Debit</th>
                                    <th class="text-end" width="180">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                @endphp

                                @foreach ($journal->details as $detail)
                                    @php
                                        $totalDebit += $detail->debit;
                                        $totalCredit += $detail->credit;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $detail->account->code }} -
                                            {{ $detail->account->name }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($detail->debit, 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($detail->credit, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            {{-- ================= TOTAL ================= --}}
                            <tfoot class="table-light">
                                <tr>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">
                                        {{ number_format($totalDebit, 2, ',', '.') }}
                                    </th>
                                    <th class="text-end">
                                        {{ number_format($totalCredit, 2, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                @endforeach
            @endif


        </div>
    </div>
@endsection
