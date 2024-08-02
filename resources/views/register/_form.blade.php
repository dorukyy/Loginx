<form method="POST" action="{{ route('register.register') }}">
    @csrf
    <div class="form-group mb-4">
        <div class="form-input">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name"
                   placeholder="Enter your name" autofocus>
        </div>
        <div class="form-input">
            <label for="surname">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname"
                   placeholder="Enter your surname" autofocus>
        </div>
        <div class="form-input">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username"
                   placeholder="Enter your username" autofocus>
        </div>


        <div class="form-input">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email"
                   placeholder="Enter your email address" autofocus>
        </div>
        <div class="form-input">
            <label for="email_confirmation">Email Confirmation</label>
            <input type="text" class="form-control" id="email_confirmation" name="email_confirmation"
                   placeholder="Enter your email address again" autofocus>
        </div>

        <div class="form-input">
            <label for="email">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="Enter your password" autofocus>
        </div>
        <div class="form-input">
            <label for="password_confirmation">Password Confirmation</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                   placeholder="Enter your password again" autofocus>
        </div>
    </div>


    <div class="mb-5">
        <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
    </div>

</form>
