<form method="POST" action="{{ route('forgot-password.setNewPassword') }}">
    @csrf

    <div class="form-group mb-4">
        <div class="form-input">
            <label>@lang('New Password')</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="@lang('Enter your new password')" autofocus>
        </div>
        <div class="form-input">
            <label>@lang('Confirm New Password')</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                   placeholder="@lang('Confirm your new password')">
        </div>
        <input type="hidden" name="token" value="{{ $token}}">
        <input type="hidden" name="email" value="{{ $email}}">

    </div>


    <div class="mb-5">
        <button class="btn btn-primary d-grid w-100" type="submit">@lang('Send')</button>
    </div>
</form>

<style>

    .form-floating-outline label {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0.75rem 0.75rem;
        pointer-events: none;
        transition: all 0.2s ease-in-out;
    }

    .form-floating-outline input:focus ~ label,
    .form-floating-outline input:not(:placeholder-shown) ~ label {
        top: -1.25rem;
        left: 0.75rem;
        font-size: 0.75rem;
        color: #495057;
    }

    .form-floating-outline input {
        padding: 1.25rem 0.75rem 0.25rem;
    }

    .form-password-toggle .input-group-text {
        cursor: pointer;
    }
</style>
