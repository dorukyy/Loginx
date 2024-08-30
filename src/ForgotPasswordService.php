<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgotPasswordService
{
    private ForgotPasswordRequest $request;
    private User $user;

    public function __construct(ForgotPasswordRequest $request)
    {
        $this->request = $request;
        $this->user = User::where('email', $this->request->email)?->first() ?? null;
    }

    public function sendMail(): \Illuminate\Http\RedirectResponse
    {
        if ($this->user == null) {
            return back()->withErrors(['email' => 'This email is not found.']);
        }
        $token = $this->user->createPasswordResetToken();

        if (!$token) {
            return back()->withErrors(['email' => 'This email is not found.']);
        }


        // Get APP_URL from config
        $url = config('app.url').'/forgot-password/reset?token='.$token;

        //add email to the url
        $url .= '&email='.$this->user->email;

        $user = $this->user;

        $message = new \stdClass(); // Added variable declaration
        Mail::send('loginx::forgot-password.mail', ['url' => $url], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Password Reset Request');
        });

        return back()->with('status', 'Password reset link sent!');
    }

}
