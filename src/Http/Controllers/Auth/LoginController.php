<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use dorukyy\loginx\Http\Requests\LoginRequest;
use dorukyy\loginx\LoginxFacade;
use dorukyy\loginx\SettingsFacade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;


class LoginController extends Controller
{

    public function loginPage()
    {

        $data = SettingsFacade::getLoginViewData();

        $loginView = LoginxFacade::getViewPath('login');


        return view($loginView, ['data' => $data]);
    }

    /**
     * @throws \Throwable
     */
    public function login(LoginRequest $request)
    {
        $data = LoginxFacade::login($request);

        $loginSettings = SettingsFacade::getLoginViewData();

        $loginSettings['inputText'] = $request->user_input ?? '';


        if ($data['success']) {
            return redirect()->route('home')->with('success', $data['message']);
        } else {
            $view = view('loginx::login.show', ['data' => $loginSettings])
                ->withErrors(['loginx' => $data['message']]);

            return response($view->render(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    public function logout(): RedirectResponse
    {
        if (auth()->check()) {
        LoginxFacade::logout();
        return redirect()->route('login.login-page')->with('success', 'You have been logged out successfully');
        } else {
            return redirect()->route('login.login-page')->withErrors(['loginx' => 'You are not logged in']);
        }


    }

}
