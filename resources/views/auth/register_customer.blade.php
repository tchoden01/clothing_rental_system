@extends('layouts.app')

@section('title', 'Customer Registration')

@push('styles')
<style>
    .back-arrow-btn {
        display: none;
    }

    .auth-scene {
        min-height: calc(100vh - 88px);
        background-image:
            linear-gradient(rgba(5, 8, 15, 0.68), rgba(5, 8, 15, 0.68)),
            url('{{ asset('images/login_bg.png') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.1rem 1rem;
    }

    .auth-shell {
        width: 100%;
        max-width: 560px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        box-shadow: 0 24px 48px rgba(0, 0, 0, 0.35);
        overflow: hidden;
    }

    .auth-head {
        text-align: center;
        padding: 1.5rem 1.5rem 0.65rem;
    }

    .auth-head h1 {
        font-size: 2rem;
        margin: 0;
        color: #1f1f1f;
        font-weight: 700;
    }

    .auth-head p {
        margin: 0.35rem 0 0;
        color: #676056;
        letter-spacing: 0.06em;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .auth-body {
        padding: 0.6rem 1.65rem 1.45rem;
    }

    .field-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #7f766a;
        margin-bottom: 0.2rem;
    }

    .form-line {
        border: 0;
        border-bottom: 1px solid #d7d2ca;
        border-radius: 0;
        background: transparent;
        padding: 0.54rem 0.1rem;
        font-size: 0.93rem;
    }

    .form-line:focus {
        box-shadow: none;
        border-color: #b49262;
    }

    .field-wrap {
        margin-bottom: 0.9rem;
    }

    .auth-submit {
        width: 100%;
        border: 0;
        border-radius: 0;
        background: #efc86f;
        color: #1f1f1f;
        font-size: 0.88rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.72rem;
        margin-top: 0.5rem;
    }

    .auth-submit:hover {
        background: #e7be62;
    }

    .auth-links {
        margin-top: 0.9rem;
        text-align: center;
        font-size: 0.82rem;
        color: #665f54;
    }

    .auth-links a {
        color: #2f5f5f;
        text-decoration: none;
        font-weight: 600;
    }

    @media (max-width: 575px) {
        .auth-head h1 {
            font-size: 1.65rem;
        }

        .auth-body {
            padding: 0.55rem 1rem 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<section class="auth-scene">
    <div class="auth-shell">
        <header class="auth-head">
            <h1>Create account</h1>
            <p>Sign up and start renting</p>
        </header>

        <div class="auth-body">
            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 field-wrap">
                        <label for="name" class="field-label">Full Name</label>
                        <input type="text" class="form-control form-line @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 field-wrap">
                        <label for="email" class="field-label">Your Email</label>
                        <input type="email" class="form-control form-line @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 field-wrap">
                        <label for="password" class="field-label">Password</label>
                        <input type="password" class="form-control form-line @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 field-wrap">
                        <label for="password_confirmation" class="field-label">Confirm Password</label>
                        <input type="password" class="form-control form-line" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="field-wrap">
                    <label for="contact_number" class="field-label">Contact Number</label>
                    <input type="text" class="form-control form-line @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                    @error('contact_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-wrap">
                    <label for="address" class="field-label">Address</label>
                    <textarea class="form-control form-line @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-submit">Sign up</button>
            </form>

            <div class="auth-links">
                Already have an account? <a href="{{ route('login') }}">Log in</a><br>
                Want to sell? <a href="{{ route('register.seller') }}">Create seller account</a>
            </div>
        </div>
    </div>
</section>
@endsection
