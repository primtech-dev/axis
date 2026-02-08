@extends('layouts.vertical', ['title' => 'Tambah Kategori'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Kategori',
        'subTitle' => 'Buat kategori produk baru',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('categories.index')],
            ['name' => 'Kategori', 'url' => route('categories.index')],
            ['name' => 'Tambah']
        ]
    ])

    @include('categories._form', [
        'category' => $category
    ])
@endsection
