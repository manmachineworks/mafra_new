@extends('backend.layouts.app')

@section('content')
    
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">{{ translate('Login With OTP') }}</h3>
            </div>
            <div class="card-body text-center">
                <label class="aiz-switch aiz-switch-success mb-0">
                    <input type="checkbox" onchange="updateSettings(this, 'login_with_otp')"
                        <?php if (get_setting('login_with_otp') == 1) {
                            echo 'checked';
                        } ?>>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">{{ translate('Phone OTP Provider') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('otp.provider.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark">{{ translate('Select Provider') }}</label>
                        <select name="provider" class="form-control" required>
                            <option value="old" @if(otp_provider() === 'old') selected @endif>{{ translate('Legacy OTP (current SMS flow)') }}</option>
                            <option value="firebase" @if(otp_provider() === 'firebase') selected @endif>{{ translate('Firebase Phone OTP') }}</option>
                        </select>
                    </div>

                    @php
                        $firebaseCredentials = env('FIREBASE_CREDENTIALS');
                        $hasFirebaseCredentials = !empty($firebaseCredentials);
                    @endphp

                    @if (otp_provider() === 'firebase' && !$hasFirebaseCredentials)
                        <div class="alert alert-warning">
                            {{ translate('Firebase OTP is enabled but FIREBASE_CREDENTIALS is not set. Please add your service account json path to .env.') }}
                        </div>
                    @endif

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type) {

            if('{{env('DEMO_MODE')}}' == 'On'){
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }
            
            var value = ($(el).is(':checked')) ? 1 : 0;
             
            $.post('{{ route('business_settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection


