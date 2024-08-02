<?php

namespace App\Http\Controllers;

use dorukyy\loginx\Http\Requests\RegisterRequest;
use dorukyy\loginx\LoginxFacade;


class RegisterController extends Controller
{

    public function registerPage()
    {
        $registerView = LoginxFacade::getRegisterView();

        return view($registerView);
    }

    public function register(RegisterRequest $request)
    {

        $data = LoginxFacade::register($request);

        if ($data['status'] == 'success') {

            return redirect()->route('home')->with('success', $data['message']);
        }
        else {
            return view('loginx::register.show')->withErrors(['error' => $data['message']]);
        }


    }

}
