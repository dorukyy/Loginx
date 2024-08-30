<?php

namespace App\Http\Controllers\Auth;

use dorukyy\loginx\Http\Requests\RegisterRequest;
use dorukyy\loginx\LoginxFacade;
use dorukyy\loginx\SettingsFacade;
use App\Http\Controllers\Controller;


class RegisterController extends Controller
{

    public function registerPage()
    {
        $data = SettingsFacade::getRegisterViewData();
        if (SettingsFacade::getIsReferralSystem()) {
            $referralCode = request()->query('referral_code');
        }

        $registerView = LoginxFacade::getViewPath('register');

        return view($registerView, ['data' => $data]);
    }

    public function register(RegisterRequest $request)
    {

        $result = LoginxFacade::register($request);
        $data = SettingsFacade::getRegisterViewData();

        // Merge validated data with existing data
        $data = array_merge($data, $request->validated());

        unset($data['password']);
        unset($data['password_confirmation']);

        if ($result['success']) {
            return redirect()->route('home')->with('success', $result['message']);
        } else {
            $view = view('loginx::register.show', ['data' => $data])->withErrors(['loginx' => $result['message']]);
            return response($view->render(), 422);
        }

    }

}
