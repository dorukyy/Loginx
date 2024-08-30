<form method="POST" action="{{ route('login.login') }}">
    @csrf
    <div class="form-group mb-2">
        <div class="form-group">
            <div class="form-input">
                <label>{{\dorukyy\loginx\LoginxFacade::getInputText()}}</label>
                <input type="text" class="form-control" id="user_input" name="user_input"
                       placeholder="Enter your email or username" autofocus @if(isset($data['inputText']))
                           value="{{$data['inputText']}}"
                    @endif
                >
                @error('user_input')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-input">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Enter your password">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                    Remember Me
                </label>
            </div>
        </div>
        @if($data['showRecaptcha']==1)
            <div class="form-group">
                <div class="form-input center-recaptcha">
                    <div class="cf-turnstile" data-sitekey={{$data['recaptchaSiteKey']}}></div>
                </div>
            </div>
        @endif

        <div class="mb-3 mt-2">
            <button class="btn btn-primary d-grid w-100" type="submit">@lang('Sign in')</button>
        </div>
    </div>
</form>

<a href="{{route('forgot-password.show-reset-form')}}" class="float-end mt-2">
    <span>Forgot Password?</span>
</a>

<style>
    .error {
        margin-top: 1%;
        padding: 0.1%;
        color: red;
        font-size: 12px;
    }
</style>
