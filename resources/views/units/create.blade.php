@extends('layouts.vertical', ['title' => 'Tambah Unit'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Unit',
        'subTitle' => 'Buat satuan produk baru',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('units.index')],
            ['name' => 'Unit', 'url' => route('units.index')],
            ['name' => 'Tambah']
        ]
    ])

    @include('units._form', [
        'unit' => $unit
    ])
@endsection
