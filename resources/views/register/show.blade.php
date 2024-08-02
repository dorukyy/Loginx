<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        @if(isset($errors))
            @foreach ($errors->getBag('default')->all() as $error)
                <div class="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif
        @include('loginx::register._form')
        <div class="login-link">
            <span>Already have an account?</span>
            <a href="{{route('login.login-page')}}">Login</a>
        </div>
    </div>
</body>
</html>

<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .register-container {
            margin-top: 5%;
            margin-bottom: 5%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
        }
        .register-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .register-container form {
            display: flex;
            flex-direction: column;
        }
        .register-container input {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .register-container button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #0056b3;
        }
        .register-container .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-container .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .register-container .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
