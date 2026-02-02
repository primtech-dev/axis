@extends('layouts.base', ['title' => 'Login'])

@section('content')
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="auth-brand mb-4">
                                <a class="logo-dark" href="/">
                                    <img src="{{asset('images/logo.png')}}" alt="" height="30">
                                </a>
                                <a class="logo-light" href="/">
                                <img src="{{asset('images/logo.png')}}" alt="" height="30">
                                </a>
                                <p class="text-muted w-lg-75 mt-3">Let’s get you signed in. Enter your email and password to
                                    continue.</p>
                            </div>
                            <div class="">
                                <form action="{{route('login')}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="userEmail">Email address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" id="userEmail" name="email" placeholder="you@example.com" required type="email" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="userPassword">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" id="userPassword" name="password" placeholder="••••••••" required type="password" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input form-check-input-light fs-14" id="rememberMe"
                                                type="checkbox" />
                                            <label class="form-check-label" for="rememberMe">Keep me signed in</label>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary fw-semibold py-2" type="submit">Sign In</button>
                                    </div>
                                    @if ($errors->any())
                                        <div class="alert alert-danger mt-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-muted mt-4 mb-0">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> By <span class="fw-semibold">Primtech</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- end auth-fluid-->
@endsection
