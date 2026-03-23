@extends('layouts.app')

@section('title', 'Seller Registration')

@push('styles')
<style>
    .back-arrow-btn {
        display: none;
    }

    .seller-onboarding {
        min-height: calc(100vh - 90px);
        background: #f4f4f1;
        display: flex;
        flex-direction: column;
    }

    .onboarding-top {
        border-bottom: 1px solid #e4e2dd;
        background: #fff;
        padding: 1rem 1.5rem;
    }

    .progress-rail {
        max-width: 1240px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .rail-step {
        font-size: 0.72rem;
        letter-spacing: 0.12em;
        color: #b2aea7;
        text-transform: uppercase;
        font-weight: 700;
    }

    .rail-step-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.42rem;
    }

    .rail-step.active {
        color: #2f2a25;
    }

    .rail-step.active .count {
        color: #800020;
    }

    .rail-track {
        height: 3px;
        background: #ded9d0;
        border-radius: 999px;
        overflow: hidden;
    }

    .rail-fill {
        height: 100%;
        background: #800020;
        width: 0;
        transition: width 0.25s ease;
    }

    .onboarding-stage {
        text-align: center;
        color: #7a746b;
        font-size: 0.82rem;
        margin-top: 0.6rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .onboarding-main {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .onboarding-panel {
        width: 100%;
        max-width: 760px;
        text-align: center;
    }

    .onboard-step {
        display: none;
        animation: fadeIn 0.22s ease;
    }

    .onboard-step.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .step-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #27231f;
        margin-bottom: 0.38rem;
        letter-spacing: 0.01em;
    }

    .step-subtitle {
        color: #6e685f;
        font-size: 1.12rem;
        margin-bottom: 2.5rem;
    }

    .step-input-wrap {
        max-width: 620px;
        margin: 0 auto;
        text-align: left;
    }

    .step-label {
        display: block;
        color: #7d776d;
        font-size: 1rem;
        margin-bottom: 0.35rem;
    }

    .step-input {
        width: 100%;
        border: 0;
        border-bottom: 2px solid #d7d3cb;
        background: transparent;
        padding: 0.8rem 0.1rem;
        font-size: 1.06rem;
        color: #2a2622;
        outline: none;
    }

    .step-input:focus {
        border-bottom-color: #800020;
    }

    .file-help {
        color: #7a756c;
        font-size: 0.86rem;
        margin-top: 0.35rem;
    }

    .step-error {
        margin-top: 1.1rem;
        color: #b02a37;
        font-size: 0.92rem;
        min-height: 1.4rem;
    }

    .step-actions {
        margin-top: 2.1rem;
        display: flex;
        gap: 0.8rem;
        justify-content: center;
    }

    .btn-step {
        min-width: 180px;
        border-radius: 12px;
        border: 0;
        padding: 0.72rem 1.4rem;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .btn-prev {
        background: #ece9e2;
        color: #3f3830;
    }

    .btn-next,
    .btn-submit {
        background: #800020;
        color: #fff;
    }

    .btn-next:hover,
    .btn-submit:hover {
        background: #650019;
    }

    @media (max-width: 860px) {
        .progress-rail {
            gap: 0.6rem;
        }

        .step-title {
            font-size: 1.6rem;
        }

        .step-subtitle {
            font-size: 1rem;
        }

        .btn-step {
            min-width: 140px;
        }
    }

    @media (max-width: 620px) {
        .progress-rail {
            grid-template-columns: 1fr;
        }

        .step-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-step {
            width: 100%;
            max-width: 320px;
        }
    }
</style>
@endpush

@section('content')
<section class="seller-onboarding">
    <div class="onboarding-top">
        <div class="progress-rail">
            <div class="rail-step" data-stage="1">
                <div class="rail-step-head">
                    <span>CREATE AN ACCOUNT</span>
                    <span class="count" id="count-stage-1">1 / 4</span>
                </div>
                <div class="rail-track"><div class="rail-fill" id="fill-stage-1"></div></div>
            </div>

            <div class="rail-step" data-stage="2">
                <div class="rail-step-head">
                    <span>SHOP INFO</span>
                    <span class="count" id="count-stage-2">2 / 4</span>
                </div>
                <div class="rail-track"><div class="rail-fill" id="fill-stage-2"></div></div>
            </div>

            <div class="rail-step" data-stage="3">
                <div class="rail-step-head">
                    <span>VERIFICATION</span>
                    <span class="count" id="count-stage-3">3 / 4</span>
                </div>
                <div class="rail-track"><div class="rail-fill" id="fill-stage-3"></div></div>
            </div>

            <div class="rail-step" data-stage="4">
                <div class="rail-step-head">
                    <span>PAYMENT</span>
                    <span class="count" id="count-stage-4">4 / 4</span>
                </div>
                <div class="rail-track"><div class="rail-fill" id="fill-stage-4"></div></div>
            </div>
        </div>

        <div class="onboarding-stage" id="step-indicator">Step 1 of 7</div>
    </div>

    <div class="onboarding-main">
        <div class="onboarding-panel">
            <form id="seller-onboarding-form" method="POST" action="{{ route('register.seller.post') }}" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="onboard-step active" data-step="1">
                    <h1 class="step-title">What should we call you?</h1>
                    <p class="step-subtitle">We'll never share any of your info</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="full_name">Full Name</label>
                        <input class="step-input" id="full_name" name="full_name" type="text" data-required="true" value="{{ old('full_name') }}">
                    </div>
                </div>

                <div class="onboard-step" data-step="2">
                    <h1 class="step-title">What's your email?</h1>
                    <p class="step-subtitle">We'll use this for account updates</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="email">Email</label>
                        <input class="step-input" id="email" name="email" type="email" data-required="true" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="onboard-step" data-step="3">
                    <h1 class="step-title">Create a password</h1>
                    <p class="step-subtitle">Use at least 8 characters</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="password">Password</label>
                        <input class="step-input" id="password" name="password" type="password" minlength="8" data-required="true">
                    </div>
                </div>

                <div class="onboard-step" data-step="4">
                    <h1 class="step-title">Tell us about your shop</h1>
                    <p class="step-subtitle">This appears on your seller profile</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="shop_name">Shop Name</label>
                        <input class="step-input" id="shop_name" name="shop_name" type="text" data-required="true" value="{{ old('shop_name') }}">
                    </div>
                </div>

                <div class="onboard-step" data-step="5">
                    <h1 class="step-title">Where are you located?</h1>
                    <p class="step-subtitle">We'll help customers find nearby shops</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="location">Location</label>
                        <input class="step-input" id="location" name="location" type="text" data-required="true" value="{{ old('location') }}">
                    </div>
                </div>

                <div class="onboard-step" data-step="6">
                    <h1 class="step-title">Verification details</h1>
                    <p class="step-subtitle">These are used for verification only</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="cid_number">CID Number</label>
                        <input class="step-input" id="cid_number" name="cid_number" type="text" data-required="true" value="{{ old('cid_number') }}">

                        <label class="step-label mt-4" for="business_license">Business License</label>
                        <input class="step-input" id="business_license" name="business_license" type="file" data-required="true" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-help">Accepted formats: PDF, JPG, JPEG, PNG</div>
                    </div>
                </div>

                <div class="onboard-step" data-step="7">
                    <h1 class="step-title">Payment Information</h1>
                    <p class="step-subtitle">Where should we send your earnings?</p>
                    <div class="step-input-wrap">
                        <label class="step-label" for="bank_name">Bank Name</label>
                        <input class="step-input" id="bank_name" name="bank_name" type="text" data-required="true" value="{{ old('bank_name') }}">

                        <label class="step-label mt-4" for="account_number">Account Number</label>
                        <input class="step-input" id="account_number" name="account_number" type="text" data-required="true" value="{{ old('account_number') }}">
                    </div>
                </div>

                <div class="step-error" id="step-error">
                    @if($errors->any())
                        {{ $errors->first() }}
                    @endif
                </div>

                <div class="step-actions">
                    <button type="button" class="btn-step btn-prev" id="prev-btn">Previous</button>
                    <button type="button" class="btn-step btn-next" id="next-btn">Next</button>
                    <button type="submit" class="btn-step btn-submit d-none" id="submit-btn">Submit Registration</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const steps = Array.from(document.querySelectorAll('.onboard-step'));
        const totalSteps = steps.length;
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        const stepIndicator = document.getElementById('step-indicator');
        const stepError = document.getElementById('step-error');
        const form = document.getElementById('seller-onboarding-form');

        const serverErrorMessage = @json($errors->first());
        const serverErrorField = @json($errors->any() ? $errors->keys()[0] : null);

        let currentStepIndex = 0;

        function getStepIndexByField(fieldName) {
            const fieldStepMap = {
                full_name: 0,
                email: 1,
                password: 2,
                shop_name: 3,
                location: 4,
                cid_number: 5,
                business_license: 5,
                bank_name: 6,
                account_number: 6,
            };

            return Object.prototype.hasOwnProperty.call(fieldStepMap, fieldName)
                ? fieldStepMap[fieldName]
                : 0;
        }

        function getStageForStep(stepNumber) {
            if (stepNumber <= 3) {
                return 1;
            }
            if (stepNumber <= 5) {
                return 2;
            }
            if (stepNumber === 6) {
                return 3;
            }
            return 4;
        }

        function renderStageProgress() {
            const currentStepNumber = currentStepIndex + 1;
            const stage = getStageForStep(currentStepNumber);

            for (let stageIndex = 1; stageIndex <= 4; stageIndex++) {
                const stageElement = document.querySelector('.rail-step[data-stage="' + stageIndex + '"]');
                const fill = document.getElementById('fill-stage-' + stageIndex);

                if (stageElement) {
                    stageElement.classList.toggle('active', stageIndex <= stage);
                }

                if (!fill) {
                    continue;
                }

                if (stageIndex < stage) {
                    fill.style.width = '100%';
                } else if (stageIndex > stage) {
                    fill.style.width = '0%';
                } else {
                    if (stage === 1) {
                        fill.style.width = ((currentStepNumber / 3) * 100) + '%';
                    } else if (stage === 2) {
                        fill.style.width = (((currentStepNumber - 3) / 2) * 100) + '%';
                    } else {
                        fill.style.width = '100%';
                    }
                }
            }
        }

        function updateStepUI(clearError = true) {
            steps.forEach(function (step, index) {
                step.classList.toggle('active', index === currentStepIndex);
            });

            stepIndicator.textContent = 'Step ' + (currentStepIndex + 1) + ' of ' + totalSteps;
            prevBtn.classList.toggle('d-none', currentStepIndex === 0);

            const isFinalStep = currentStepIndex === totalSteps - 1;
            nextBtn.classList.toggle('d-none', isFinalStep);
            submitBtn.classList.toggle('d-none', !isFinalStep);

            if (clearError) {
                stepError.textContent = '';
            }

            renderStageProgress();
        }

        function validateCurrentStep() {
            const currentStep = steps[currentStepIndex];
            const requiredInputs = Array.from(currentStep.querySelectorAll('[data-required="true"]'));

            for (const input of requiredInputs) {
                const isFileInput = input.type === 'file';

                if (isFileInput && input.files.length === 0) {
                    stepError.textContent = 'Please upload the required file before continuing.';
                    input.focus();
                    return false;
                }

                if (!isFileInput && input.value.trim() === '') {
                    stepError.textContent = 'Please fill out the required field before continuing.';
                    input.focus();
                    return false;
                }

                if (input.type === 'email' && !input.checkValidity()) {
                    stepError.textContent = 'Please enter a valid email address.';
                    input.focus();
                    return false;
                }

                if (input.id === 'password' && input.value.trim().length < 8) {
                    stepError.textContent = 'Password must be at least 8 characters long.';
                    input.focus();
                    return false;
                }
            }

            return true;
        }

        nextBtn.addEventListener('click', function () {
            if (!validateCurrentStep()) {
                return;
            }

            if (currentStepIndex < totalSteps - 1) {
                currentStepIndex += 1;
                updateStepUI();
            }
        });

        prevBtn.addEventListener('click', function () {
            if (currentStepIndex > 0) {
                currentStepIndex -= 1;
                updateStepUI();
            }
        });

        form.addEventListener('submit', function (event) {
            if (!validateCurrentStep()) {
                event.preventDefault();
            }
        });

        if (serverErrorField) {
            currentStepIndex = getStepIndexByField(serverErrorField);
            updateStepUI(false);
            stepError.textContent = serverErrorMessage || 'Please correct the highlighted field and try again.';
        } else {
            updateStepUI();
        }
    });
</script>
@endpush
