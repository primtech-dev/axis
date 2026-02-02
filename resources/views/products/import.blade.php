@extends('layouts.vertical', ['title' => 'Import Produk'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Import Produk',
        'subTitle' => 'Upload data produk dari Excel dan ZIP gambar',
        'breadcrumbs' => [
            ['name' => 'Produk', 'url' => route('products.index')],
            ['name' => 'Import']
        ]
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import Produk (Excel + ZIP)</h5>
        </div>
        <div class="card-body">

            <div class="card mb-3">
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('products.import.template') }}"
                       class="btn btn-outline-primary">
                        <i data-lucide="download" class="me-1"></i>
                        Download Template Excel
                    </a>

                    <a href="{{ route('products.import.images_example') }}"
                       class="btn btn-outline-secondary">
                        <i data-lucide="image" class="me-1"></i>
                        Download Contoh ZIP Gambar
                    </a>
                </div>
            </div>

            <div class="alert alert-info mt-2 mb-4">
                <ul class="mb-0">
                    <li>Gunakan <strong>template Excel</strong> agar format sesuai sistem</li>
                    <li>Nama file gambar di Excel harus <strong>sama persis</strong> dengan isi ZIP</li>
                    <li>ZIP gambar <strong>tanpa folder</strong> (langsung file)</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('products.import.preview') }}" enctype="multipart/form-data">
            @csrf

                <div class="mb-3">
                    <label class="form-label">File Excel Produk</label>
                    <input type="file" name="excel" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ZIP Gambar Produk</label>
                    <input type="file" name="images_zip" class="form-control" required>
                </div>

                <button class="btn btn-primary">
                    Preview Import
                </button>
            </form>
        </div>
    </div>
@endsection
