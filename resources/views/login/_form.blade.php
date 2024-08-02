<form method="POST" action="{{ route('login.login') }}">
    @csrf

    <div class="form-group mb-4">
        <div class="form-input">
            <label>{{\dorukyy\loginx\LoginxFacade::getInputText()}}</label>
            <input type="text" class="form-control" id="user_input" name="user_input"
                   placeholder="Enter your email or username" autofocus>
        </div>
        <div class="form-input">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="Enter your password" autofocus>
        </div>
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="remember-me">
            <label class="form-check-label" for="remember-me">
                Remember Me
            </label>
        </div>
    </div>


    <a href="{{url('auth/forgot-password')}}" class="float-end mb-1 mt-2">
        <span>Forgot Password?</span>
    </a>

    <div class="mb-5">
        <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
    </div>
</form>

<style>
    .form-floating-outline {
        position: relative;
    }

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
