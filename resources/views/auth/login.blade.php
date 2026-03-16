@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    .back-arrow-btn {
        display: none;
    }

    .login-scene {
        min-height: calc(100vh - 88px);
        background-image:
            linear-gradient(rgba(5, 8, 15, 0.68), rgba(5, 8, 15, 0.68)),
            url('{{ asset('images/login_bg.png') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.2rem 1rem;
    }

    .login-shell {
        width: 100%;
        max-width: 460px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        box-shadow: 0 24px 48px rgba(0, 0, 0, 0.35);
        overflow: hidden;
    }

    .login-head {
        text-align: center;
        padding: 1.65rem 1.5rem 0.8rem;
    }

    .login-head h1 {
        font-size: 2rem;
        margin: 0;
        color: #1f1f1f;
        font-weight: 700;
    }

    .login-head p {
        margin: 0.4rem 0 0;
        color: #676056;
        letter-spacing: 0.06em;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .login-body {
        padding: 0.8rem 1.8rem 1.8rem;
    }

    .form-line {
        border: 0;
        border-bottom: 1px solid #d7d2ca;
        border-radius: 0;
        background: transparent;
        padding: 0.58rem 0.1rem 0.58rem 2rem;
        font-size: 0.93rem;
    }

    .form-line:focus {
        box-shadow: none;
        border-color: #b49262;
    }

    .field-wrap {
        position: relative;
        margin-bottom: 1rem;
    }

    .field-icon {
        position: absolute;
        left: 0.2rem;
        top: 50%;
        transform: translateY(-50%);
        color: #8e877a;
        font-size: 0.85rem;
    }

    .login-submit {
        width: 100%;
        border: 0;
        border-radius: 0;
        background: #b99767;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        padding: 0.72rem;
        margin-top: 0.5rem;
    }

    .login-submit:hover {
        background: #a98759;
    }

    .login-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
    }

    .login-meta a {
        color: #6c6458;
        text-decoration: none;
    }

    .divider-text {
        text-align: center;
        color: #71695d;
        font-size: 0.8rem;
        margin: 0.95rem 0 0.7rem;
    }

    .social-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.45rem;
    }

    .social-btn {
        border: 0;
        color: #fff;
        font-size: 0.72rem;
        padding: 0.46rem 0.35rem;
        border-radius: 4px;
    }

    .social-google { background: #dd4b39; }
    .social-facebook { background: #3b5998; }
    .social-apple { background: #111; }

    .register-links {
        margin-top: 1rem;
        text-align: center;
        font-size: 0.82rem;
        color: #665f54;
    }

    .register-links a {
        color: #2f5f5f;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 575px) {
        .login-head h1 {
            font-size: 1.6rem;
        }

        .login-body {
            padding: 0.75rem 1rem 1.2rem;
        }
    }
</style>
@endpush

@section('content')
<section class="login-scene">
    <div class="login-shell">
        <header class="login-head">
            <h1>Login To Your Account</h1>
            <p>Login credentials to access your account.</p>
        </header>

        <div class="login-body">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="field-wrap">
                    <i class="bi bi-person field-icon"></i>
                    <input
                        type="email"
                        class="form-control form-line @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter Your Email"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <i class="bi bi-lock field-icon"></i>
                    <input
                        type="password"
                        class="form-control form-line @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Enter Your Password"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login-meta">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <a href="#">Forgot Password?</a>
                </div>

                <button type="submit" class="login-submit">Submit Now</button>
            </form>

            <div class="divider-text">Or login with</div>
            <div class="social-row">
                <button type="button" class="social-btn social-google">Google</button>
                <button type="button" class="social-btn social-facebook">Facebook</button>
                <button type="button" class="social-btn social-apple">Apple</button>
            </div>

            <div class="register-links">
                Need an account?
                <a href="{{ route('register') }}">Customer</a>
                /
                <a href="{{ route('register.seller') }}">Seller</a>
            </div>
        </div>
    </div>
</section>
@endsection
