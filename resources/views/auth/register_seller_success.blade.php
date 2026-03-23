@extends('layouts.app')

@section('title', 'Seller Registration Submitted')

@push('styles')
<style>
    .seller-success-shell {
        min-height: calc(100vh - 260px);
        padding-bottom: 7rem;
    }

    .swal2-container.swal2-center {
        padding-bottom: 6rem;
    }

    .seller-success-popup {
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .seller-success-shell {
            min-height: calc(100vh - 220px);
            padding-bottom: 5rem;
        }

        .swal2-container.swal2-center {
            padding-bottom: 4rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5 text-center seller-success-shell">
    <p class="text-muted">Preparing your registration confirmation...</p>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Registration Submitted!',
            customClass: {
                popup: 'seller-success-popup'
            },
            html: `
                <div style="text-align:left;line-height:1.6;">
                    <p><strong>You're Almost There! 🎉</strong></p>
                    <p>Your seller registration has been submitted successfully.<br>
                    It is currently pending admin approval.</p>
                    <p>Once approved, you'll be able to list and rent your items.</p>
                    <p>We'll notify you via email once your account is verified.</p>
                </div>
            `,
            confirmButtonText: 'Go to Home',
            confirmButtonColor: '#800020',
            allowOutsideClick: false,
            allowEscapeKey: true
        }).then(function () {
            window.location.href = "{{ route('home') }}";
        });
    });
</script>
@endpush
