@extends('layouts.vertical', ['title' => 'Edit Customer'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit Customer',
        'subTitle' => 'Perbarui data pelanggan',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('customers.index')],
            ['name' => 'Customer', 'url' => route('customers.index')],
            ['name' => 'Edit']
        ]
    ])

    @include('customers._form', [
        'customer' => $customer
    ])
@endsection
