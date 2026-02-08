@extends('layouts.vertical', ['title' => 'Edit Kategori'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit Kategori',
        'subTitle' => 'Perbarui data kategori',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('categories.index')],
            ['name' => 'Kategori', 'url' => route('categories.index')],
            ['name' => 'Edit']
        ]
    ])

    @include('categories._form', [
        'category' => $category
    ])
@endsection
