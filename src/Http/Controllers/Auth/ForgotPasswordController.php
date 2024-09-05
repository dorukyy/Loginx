<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use dorukyy\loginx\ForgotPasswordService;
use dorukyy\loginx\Http\Requests\ForgotPasswordRequest;
use dorukyy\loginx\Http\Requests\SetNewPasswordRequest;
use dorukyy\loginx\LoginxFacade;
use dorukyy\loginx\Models\PasswordResetToken;
use dorukyy\loginx\ResetPasswordService;
use dorukyy\loginx\SettingsFacade;

class ForgotPasswordController extends Controller
{

    public function showResetForm()
    {
        $data = SettingsFacade::getForgotPasswordViewData();
        return view(LoginxFacade::getViewPath('forgotPassword'), ['data' => $data]);
    }

    public function sendMail(ForgotPasswordRequest $request)
    {
        $forgotPasswordService = new ForgotPasswordService($request);
        return $forgotPasswordService->sendMail();
    }

    public function reset()
    {
        $token = request('token');
        $email = request('email');

        // Check if token is valid
        $user = User::where('email', $email)->first();
        $passwordResetToken = PasswordResetToken::where('token', $token)->first();

        if (!$user) {
            return redirect()->route('login.login-page')->withErrors(['email' => 'This email is not found.']);
        }

        if ($passwordResetToken == null || $passwordResetToken->user_id != $user->id) {
            return redirect()->route('login.login-page')->withErrors(['token' => 'This token is invalid.']);
        }

        return view('loginx::forgot-password.new-pass', ['token' => $token, 'email' => $email])->with('success', 'You can change your password now.');

    }

    public function setNewPassword(SetNewPasswordRequest $request)
    {

        $resetPasswordService = new ResetPasswordService($request);
        $data = $resetPasswordService->run();

        if ($data['status'] == 0) {
            return redirect()->back()->withErrors(['loginx' => $data['message']]);
        }
        if ($data['status'] == 1) {
            return redirect()->route('login.login-page')->with('success', 'Password has been changed.');
        }

    }

}
