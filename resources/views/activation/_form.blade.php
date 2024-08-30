<form method="POST" action="{{ route('activation.resend') }}">
    @csrf
    <div class="form-group mb-4">
        <div class="form-input">
            <label>@lang('Email')</label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="Enter your email" autofocus>
            @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
        </div>

    </div>

    @if($data['showRecaptcha']==1)
            <div class="form-group">
                <div class="form-input center-recaptcha">
                    <div class="cf-turnstile" data-sitekey={{$data['recaptchaSiteKey']}}></div>
                </div>
            </div>
        @endif


    <div class="mb-3">
        <button class="btn btn-primary d-grid w-100" type="submit">@lang('Send')</button>
    </div>
</form>

<style>
    .error {
        margin-top: 1%;
        padding: 0.1%;
        color: red;
        font-size: 12px;
    }
</style>
