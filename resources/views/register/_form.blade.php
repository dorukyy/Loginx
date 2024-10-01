<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <div class="form-group row">
            <div class="form-input col-6">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       placeholder="Enter your name" autofocus value="{{ old('name', $data['name'] ?? '') }}">
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-input col-6">
                <label for="surname">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname"
                       placeholder="Enter your surname" value="{{ old('surname', $data['surname'] ?? '') }}">
                @error('surname')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if($data['showUsername']==1)
            <div class="form-group">
                <div class="form-input">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                           placeholder="Enter your username" value="{{ old('username', $data['username'] ?? '') }}">
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        <div class="form-group row">
            <div class="form-input col-6">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email"
                       placeholder="Enter your email address" value="{{ old('email', $data['email'] ?? '') }}">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-input col-6">
                <label for="email_confirmation">Email Confirmation</label>
                <input type="text" class="form-control" id="email_confirmation" name="email_confirmation"
                       placeholder="Enter your email address again" value="{{ old('email_confirmation') }}">
                @error('email_confirmation')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="form-input col-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Enter your password">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-input col-6">
                <label for="password_confirmation">Password Confirmation</label>
                <input type="password" class="form-control" id="password_confirmation"
                       name="password_confirmation" placeholder="Enter your password again">
                @error('password_confirmation')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if($data['showPhone']==1)
            <div class="form-group row">
                <div class="form-input col-3">
                    <label for="phone_code">Phone</label>
                    <select class="form-control" id="phone_code" name="phone_code">
                        <option value="">@lang('Code')</option>
                        @foreach(\dorukyy\loginx\Models\Country::all() as $country)
                            <option value="{{$country->phone_code}}" {{ old('phone_code') == $country->phone_code ? 'selected' : '' }}>
                                {{$country->flag . $country->name . '(+'. $country->phone_code. ')'}}
                            </option>
                        @endforeach
                    </select>
                    @error('phone_code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-input col-9">
                    <label for="phone">Phone Number</label>
                    <input type="number" class="form-control" id="phone" name="phone"
                           placeholder="Enter your phone number" value="{{ old('phone', $data['phone'] ?? '') }}">
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if($data['showBirthdate']==1)
            <div class="form-group">
                <div class="form-input">
                    <label for="birth_date">Birth Date</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if($data['showCountry']==1)
            <div class="form-group">
                <div class="form-input">
                    <label for="country">Country</label>
                    <select class="form-control" id="country" name="country">
                        <option value="">@lang('Select your country')</option>
                        @foreach(\dorukyy\loginx\Models\Country::all() as $country)
                            <option value="{{$country->id}}" {{ old('country') == $country->id ? 'selected' : '' }}>
                                {{$country->flag. ' ' . $country->name}}
                            </option>
                        @endforeach
                    </select>
                    @error('country')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if($data['showCity']==1)
            <div class="form-group">
                <div class="form-input">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city"
                           placeholder="Enter your city" value="{{ old('city', $data['city'] ?? '') }}">
                    @error('city')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if($data['showAddress']==1)
            <div class="form-group">
                <div class="form-input">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                           placeholder="Enter your address" value="{{ old('address', $data['address'] ?? '') }}">
                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        @if($data['showRecaptcha']==1)
            <div class="form-group ">
                <div class="form-input">
                    <div class="cf-turnstile d-grid w-100" data-sitekey={{$data['recaptchaSiteKey']}}></div>
                </div>
            </div>
        @endif

        <div class="mb-2 p-2">
            <button class="btn btn-primary d-grid w-100" type="submit">Sign up</button>
        </div>
    </div>
</form>

@if($data['showBirthdate']==1)
    <script>
        $(document).ready(function () {
            $('#birth_date').attr('max', new Date().toISOString().split("T")[0]);
        });
    </script>
@endif

<style>
    .error {
        margin-top: 1%;
        padding: 0.1%;
        color: red;
        font-size: 12px;
    }
</style>
