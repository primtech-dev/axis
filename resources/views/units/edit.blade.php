@extends('layouts.vertical', ['title' => 'Edit Unit'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit Unit',
        'subTitle' => 'Perbarui data satuan',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('units.index')],
            ['name' => 'Unit', 'url' => route('units.index')],
            ['name' => 'Edit']
        ]
    ])

    @include('units._form', [
        'unit' => $unit
    ])
@endsection
