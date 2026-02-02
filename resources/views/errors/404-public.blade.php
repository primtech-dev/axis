@extends('landing.layout')

@section('title', 'Halaman Tidak Ditemukan | Fasilitas Dana MTF')

@section('content')
    {{-- 404 Section --}}
    <section class="error-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    {{-- 404 Illustration --}}
                    <div class="error-illustration mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" class="error-svg">
                            {{-- Background circles --}}
                            <circle cx="400" cy="300" r="250" fill="#E6F2FF" opacity="0.3"/>
                            <circle cx="400" cy="300" r="180" fill="#0047AB" opacity="0.1"/>

                            {{-- 404 Text --}}
                            <text x="400" y="320" text-anchor="middle" class="error-number">404</text>

                            {{-- Sad face --}}
                            <circle cx="340" cy="250" r="8" fill="#0047AB"/>
                            <circle cx="460" cy="250" r="8" fill="#0047AB"/>
                            <path d="M 320 370 Q 400 340 480 370" stroke="#0047AB" stroke-width="6" fill="none" stroke-linecap="round"/>

                            {{-- Document icon --}}
                            <rect x="300" y="420" width="200" height="140" rx="10" fill="#0047AB" opacity="0.2"/>
                            <line x1="330" y1="450" x2="470" y2="450" stroke="#0047AB" stroke-width="4" stroke-linecap="round"/>
                            <line x1="330" y1="480" x2="470" y2="480" stroke="#0047AB" stroke-width="4" stroke-linecap="round"/>
                            <line x1="330" y1="510" x2="420" y2="510" stroke="#0047AB" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </div>

                    {{-- Error Title --}}
                    <h1 class="error-title">Halaman Tidak Ditemukan</h1>

                    {{-- Error Message --}}
                    <p class="error-message">
                        Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.
                    </p>


                    {{-- Quick Links --}}
                    <div class="error-links">
                        <a href="{{ route('landing.index') }}" class="btn btn-primary btn-lg me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                            </svg>
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-lg mb-2">
                            Lihat Berita Terkini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
