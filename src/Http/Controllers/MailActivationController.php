<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use dorukyy\loginx\Http\Requests\MailActivationRequest;
use dorukyy\loginx\Models\MailActivationToken;
use dorukyy\loginx\SettingsFacade;

class MailActivationController extends Controller
{
    public function showResendActivationMailForm()
    {
        $data = SettingsFacade::getActivationViewData();
        return view('loginx::activation.resend', ['data' => $data]);
    }

    public function resendActivationMail(MailActivationRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $resendSettings = SettingsFacade::getActivationViewData();

        if (!$user) {
            $view = view('loginx::activation.resend', ['data' => $resendSettings])
                ->withErrors(['email' => 'This email is not found.']);
            return response($view->render(), 422);
        } else {
            if ($user->email_verified_at != null) {
                $view = view('loginx::activation.resend', ['data' => $resendSettings])
                    ->withErrors(['email' => 'This email is already activated.']);
                return response($view->render(), 422);
            } else {
                $mailTokens = MailActivationToken::where('user_id', $user->id)->get();

                foreach ($mailTokens as $mailToken) {
                    if ($mailToken->created_at->addSeconds(180) > now()) {
                        $view = view('loginx::activation.resend', ['data' => $resendSettings])
                            ->withErrors(['email' => config('loginx.messages.activationMailAlreadySent')]);
                        return response($view->render(), 422);
                    }

                }
                $user->sendEmailVerification();
                return redirect()->route('login.login')->with('success', config('loginx.messages.activationMailSent'));
            }
        }

    }

    public function activate()
    {
        $token = request('token');
        $mailToken = MailActivationToken::where('token', $token)->first();
        $activationSettings = SettingsFacade::getActivationViewData();

        if ($mailToken && $mailToken->expires_at > now()) {
            $user = User::find($mailToken->user_id);
            $user->email_verified_at = now();
            $user->save();
            $mailToken->is_used = true;
            $mailToken->save();
            return redirect()->route('login.login')->with('success', config('loginx.messages.accountActivated'));


        }
        $view = view('loginx::login.show', ['data' => $activationSettings])
            ->withErrors(['activation' => 'Invalid token.']);
        return redirect()->route('login.login-page')->withErrors(['loginx' => 'Invalid token.']);
    }

}
