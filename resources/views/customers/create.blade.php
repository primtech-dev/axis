@extends('layouts.vertical', ['title' => 'Tambah Customer'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Customer',
        'subTitle' => 'Tambah data pelanggan baru',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('customers.index')],
            ['name' => 'Customer', 'url' => route('customers.index')],
            ['name' => 'Tambah']
        ]
    ])

    @include('customers._form', [
        'customer' => $customer
    ])
@endsection
