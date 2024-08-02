<?php

namespace App\Http\Controllers;

use dorukyy\loginx\Http\Requests\LoginRequest;
use dorukyy\loginx\LoginxFacade;


class LoginController extends Controller
{

    public function loginPage()
    {
        $loginView = LoginxFacade::getLoginViewPath();


        return view($loginView);
    }

    public function login(LoginRequest $request)
    {
        $data = LoginxFacade::login($request);

        if ($data['status'] == 'success') {

            return redirect()->route('home')->with('success', $data['message']);
        }
        else {
            return view('loginx::login.show')->withErrors(['error' => $data['message']]);
        }

    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');

    }

}
