@extends('auth.layouts.authentication')

@section('content')
    <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
        <section class="bg-white overflow-hidden">
            <div class="row">
                <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                    <div class="card shadow-none rounded-0 border-0">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <img src="{{ uploaded_asset(get_setting('customer_login_page_image')) }}" alt="{{ translate('Customer Login Page Image') }}" class="img-fit h-100">
                            </div>

                            <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content">
                                <div class="size-48px mb-3 mx-auto mx-lg-0">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                </div>

                                <div class="text-center text-lg-left">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-primary text-uppercase">{{ translate('Phone Login (Firebase OTP)') }}</h1>
                                    <h5 class="fs-14 fw-400 text-dark">{{ translate('Use your phone number to get a one-time code') }}</h5>
                                </div>

                                <div class="pt-3">
                                    <div class="form-group mb-3">
                                        <label class="fs-12 fw-700 text-soft-dark">{{ translate('Phone (with country code)') }}</label>
                                        <input type="tel" id="firebase-phone-input" class="form-control rounded-0" placeholder="+1 555 123 4567">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fs-12 fw-700 text-soft-dark">{{ translate('Verification Code') }}</label>
                                        <input type="text" id="firebase-otp-input" class="form-control rounded-0" placeholder="{{ translate('6 digit code') }}">
                                    </div>

                                    <div id="firebase-recaptcha-container" class="mb-3"></div>

                                    <div class="d-flex align-items-center mb-3">
                                        <button type="button" id="firebase-send-otp" class="btn btn-outline-primary mr-2 rounded-0">
                                            {{ translate('Send OTP') }}
                                        </button>
                                        <button type="button" id="firebase-verify-otp" class="btn btn-primary rounded-0">
                                            {{ translate('Verify & Login') }}
                                        </button>
                                    </div>

                                    <div class="alert alert-info d-none" id="firebase-message"></div>
                                    <div class="alert alert-danger d-none" id="firebase-error"></div>

                                    <p class="fs-12 text-gray mb-0 mt-3">
                                        {{ translate('Prefer the previous flow?') }}
                                        <a href="{{ route('user.login') }}" class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Use legacy OTP login') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mr-4 mr-md-0">
                            <a href="{{ url()->previous() }}" class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
                                <i class="las la-arrow-left fs-20 mr-1"></i>
                                {{ translate('Back to Previous Page')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js';
        import { getAuth, RecaptchaVerifier, signInWithPhoneNumber } from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js';

        const firebaseConfig = {
            // TODO: paste your Firebase web config here
            apiKey: '',
            authDomain: '',
            projectId: '',
            storageBucket: '',
            messagingSenderId: '',
            appId: '',
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const messageBox = document.getElementById('firebase-message');
        const errorBox = document.getElementById('firebase-error');
        const sendBtn = document.getElementById('firebase-send-otp');
        const verifyBtn = document.getElementById('firebase-verify-otp');
        const phoneInput = document.getElementById('firebase-phone-input');
        const otpInput = document.getElementById('firebase-otp-input');

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        auth.useDeviceLanguage();

        let recaptchaVerifier;
        let confirmationResult = null;

        const showMessage = (text) => {
            messageBox.classList.remove('d-none');
            messageBox.textContent = text;
            errorBox.classList.add('d-none');
        };

        const showError = (text) => {
            errorBox.classList.remove('d-none');
            errorBox.textContent = text;
            messageBox.classList.add('d-none');
        };

        const setupRecaptcha = () => {
            if (!recaptchaVerifier) {
                recaptchaVerifier = new RecaptchaVerifier(auth, 'firebase-recaptcha-container', {
                    size: 'normal',
                });
                recaptchaVerifier.render();
            } else {
                recaptchaVerifier.clear();
                recaptchaVerifier = new RecaptchaVerifier(auth, 'firebase-recaptcha-container', {
                    size: 'normal',
                });
                recaptchaVerifier.render();
            }
        };

        sendBtn.addEventListener('click', async () => {
            const phone = phoneInput.value.trim();
            if (!phone) {
                showError('{{ translate('Please enter a valid phone number with country code.') }}');
                return;
            }
            setupRecaptcha();
            sendBtn.disabled = true;
            showMessage('{{ translate('Sending OTP...') }}');
            try {
                confirmationResult = await signInWithPhoneNumber(auth, phone, recaptchaVerifier);
                showMessage('{{ translate('OTP sent. Please check your phone.') }}');
            } catch (error) {
                showError(error.message || '{{ translate('Failed to send OTP.') }}');
            } finally {
                sendBtn.disabled = false;
            }
        });

        verifyBtn.addEventListener('click', async () => {
            if (!confirmationResult) {
                showError('{{ translate('Please request an OTP first.') }}');
                return;
            }
            const code = otpInput.value.trim();
            if (!code) {
                showError('{{ translate('Please enter the received OTP code.') }}');
                return;
            }
            verifyBtn.disabled = true;
            showMessage('{{ translate('Verifying code...') }}');
            try {
                const result = await confirmationResult.confirm(code);
                const idToken = await result.user.getIdToken();

                const response = await fetch('{{ route('auth.firebase.phone') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ idToken }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Login failed');
                }

                window.location.href = data.redirect || '{{ route('dashboard') }}';
            } catch (error) {
                showError(error.message || '{{ translate('Verification failed.') }}');
            } finally {
                verifyBtn.disabled = false;
            }
        });
    </script>
@endsection
