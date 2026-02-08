@extends('layouts.vertical', ['title' => 'Laporan Hutang Supplier'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Laporan Hutang Supplier',
        'subTitle' => 'Ringkasan hutang per supplier',
    ])

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Supplier</th>
                        <th class="text-end">Total Hutang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td class="text-end">
                                {{ number_format($s->total_hutang ?? 0, 2, ',', '.') }}
                            </td>
                            <td class="text-center">

                                <a href="{{ route('suppliers.show', $s->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="ti ti-eye"></i>
                                </a>

                                <a href="{{ route('reports.supplier-payables.pdf', ['supplier_id' => $s->id]) }}"
                                    class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="ti ti-printer"></i>
                                </a>

                                <a href="{{ route('reports.supplier-payables.excel', ['supplier_id' => $s->id]) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="ti ti-file-spreadsheet"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
