<form method="POST" action="{{ route('forgot-password.sendMail') }}">
    @csrf

    <div class="form-group mb-4">
        <div class="form-input">
            <label>Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="Enter your email" autofocus>
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
