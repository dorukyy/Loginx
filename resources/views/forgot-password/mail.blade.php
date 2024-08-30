<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>@lang('Forgot Password')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        .btn-primary a {
            color: #fff;
            text-decoration: none;
        }

        .btn-primary a:hover {
            text-decoration: underline;
        }

        .center {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header mb-2">
        <img src="https://cdn-icons-png.flaticon.com/512/6195/6195700.png" alt="Logo">
        <h2>@lang('Forgot Password')</h2>
    </div>

    <p class="center">@lang('You have requested a new password.')</p>
    <p class="center">@lang('Click the button below to reset your password.')</p>
    <div class="center">
        <button class="btn btn-primary"><a href="{{$url}}">@lang('Reset Password')</a></button>
    </div>
</div>
</body>
</html>
