@extends('layouts.base', ['title' => 'Lock Screen'])

@section('content')
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="auth-brand mb-4">
                                <a class="logo-dark" href="/">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                            <span class="avatar-title">
                                                <i class="fs-md" data-lucide="sparkles"></i>
                                            </span>
                                        </span>
                                        <span class="logo-text text-body fw-bold fs-xl">Simple</span>
                                    </span>
                                </a>
                                <a class="logo-light" href="/">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                            <span class="avatar-title">
                                                <i class="fs-md" data-lucide="sparkles"></i>
                                            </span>
                                        </span>
                                        <span class="logo-text text-white fw-bold fs-xl">Simple</span>
                                    </span>
                                </a>
                                <p class="text-muted w-lg-75 mt-3">This screen is locked. Enter your password to continue
                                </p>
                            </div>
                            <div class="text-center mb-4">
                                <img alt="thumbnail" class="rounded-circle img-thumbnail avatar-xxl mb-2"
                                    src="/images/users/user-2.jpg" />
                                <span>
                                    <h5 class="my-0 fw-semibold">Maxine Kennedy</h5>
                                    <h6 class="my-0 text-muted">Admin Head</h6>
                                </span>
                            </div>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label" for="userPassword">Password <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" id="userPassword" placeholder="••••••••" required=""
                                            type="password" />
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary fw-semibold py-2" type="submit">Unlock</button>
                                </div>
                            </form>
                            <p class="text-muted text-center mt-4 mb-0">
                                Not you? Return to <a class="text-decoration-underline link-offset-3 fw-semibold"
                                    href="auth-sign-in.html">Sign in</a>
                            </p>
                        </div>
                    </div>
                    <p class="text-center text-muted mt-4 mb-0">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Simple — by <span class="fw-semibold">Coderthemes</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- end auth-fluid-->
@endsection
