@extends('layouts.vertical', ['title' => 'Preview Import Produk'])

@section('content')

    @include('layouts.shared.page-title', [
        'title' => 'Preview Import Produk',
        'subTitle' => 'Periksa data sebelum benar-benar di-import',
        'breadcrumbs' => [
            ['name' => 'Produk', 'url' => route('products.index')],
            ['name' => 'Import'],
            ['name' => 'Preview']
        ]
    ])

    <div class="card">
        <div class="card-body">

            @if(!empty($fatalErrors))
                <div class="alert alert-danger">
                    <strong>Import diblokir:</strong>
                    <ul class="mb-0">
                        @foreach($fatalErrors as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- WARNING --}}
            @if(!empty($warnings))
                <div class="alert alert-warning">
                    <strong>Peringatan (boleh lanjut):</strong>
                    <ul class="mb-0">
                        @foreach($warnings as $warn)
                            <li>{{ $warn }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Row</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Varian</th>
                    <th>Unit</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($preview as $p)
                    <tr>
                        <td>{{ $p['row'] }}</td>
                        <td>{{ $p['product'] }}</td>
                        <td>
                            @if(!empty($p['categories']))
                                @foreach($p['categories'] as $cat)
                                    @if($cat['exists'])
                                        <span class="badge bg-success me-1">
                                            {{ $cat['name'] }}
                                        </span>
                                                            @else
                                                                <span class="badge bg-info me-1">
                                            {{ $cat['name'] }} (new)
                                        </span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $p['variant'] }}</td>
                        <td>{{ $p['unit'] }}</td>
                        <td>
                            @if($p['status'] === 'OK')
                                <span class="badge bg-success">OK</span>
                            @elseif($p['status'] === 'WARNING')
                                <span class="badge bg-warning">Warning</span>
                            @else
                                <span class="badge bg-danger">Error</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <form method="POST" action="{{ route('products.import.process') }}">
                @csrf
                <input type="hidden" name="excel_path" value="{{ $excelPath }}">
                <input type="hidden" name="zip_path" value="{{ $zipPath }}">

                <button class="btn btn-primary" {{ !empty($fatalErrors) ? 'disabled' : '' }}>
                    Confirm Import
                </button>
            </form>

        </div>
    </div>

@endsection
