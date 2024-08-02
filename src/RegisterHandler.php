<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Models\BlockedIp;
use dorukyy\loginx\Models\BlockedMailProvider;
use dorukyy\loginx\Models\RegisterRequest;
use dorukyy\loginx\Models\Timeout;
use Illuminate\Support\Facades\Hash;

class RegisterHandler
{
    private string $email;
    private string $username;
    private string $ip;
    private string $message;
    private \dorukyy\loginx\Http\Requests\RegisterRequest $request;

    public function __construct(\dorukyy\loginx\Http\Requests\RegisterRequest $request)
    {
        $this->email = $request->email ?? null;
        $this->username = $request->username ?? null;
        $this->ip = $request->ip();
        $this->request = $request;
    }

    /*
     * Check if the mail provider is in the list of blocked mail providers
     */
    private function checkMailProviderIsBlocked(): bool
    {
        $emailProvider = explode('@', $this->email)[1];
        $isEmailProviderFound = BlockedMailProvider::where('url', $emailProvider)->exists();
        if ($isEmailProviderFound) {
            $this->message = config('loginx.mail_provider_blocked_message');
            return true;
        }
        return false;

    }

    private function createFailedRegisterRequest(): void
    {
        RegisterRequest::create([
            'email' => $this->email ?? null,
            'username' => $this->username ?? null,
            'ip' => $this->ip,
            'is_failed' => true,
        ]);
    }

    private function checkRegisterAttemptsWithIp(): bool
    {
        $registerRequests = RegisterRequest::where('ip', $this->ip)->get();
        $maxRegisterAttempts = LoginxFacade::getMaxRegisterAttempts();
        if ($registerRequests->count() > $maxRegisterAttempts) {
            Timeout::create([
                'ip' => $this->ip,
                'unblocked_at' => now()->addSeconds(LoginxFacade::getRegisterTimeout()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->message = config('loginx.max_failed_register_ip_message');
            return true;
        }
        return false;
    }

    private function isTimedOut(): bool
    {
        $isTimeout = Timeout::where('ip', $this->ip)->exists();
        if ($isTimeout) {
            $this->message= str_replace('{unblocked_at}', Timeout::where('ip', $this->ip)->first()->unblocked_at,
                        config('loginx.ip_timeout_message'));
            return true;
        }
        return false;

    }

    private function isIpBlocked(): bool
    {
        $isIpBlocked = BlockedIp::where('ip', $this->ip)->exists();
        if ($isIpBlocked) {
            $this->message = 'Your IP address is blocked.';
            return true;
        }
        return false;

    }

    public function register()
    {
        //Check if the ip is blocked
        if ($this->isIpBlocked()) {
            $this->createFailedRegisterRequest();
            return ['status' => 'failed', 'message' => $this->message];
        }
        //Check if the ip is timed out
        if ($this->isTimedOut()) {
            $this->createFailedRegisterRequest();
            return ['status' => 'failed', 'message' => $this->message];
        }
        //Check if the mail provider is blocked
        if ($this->checkMailProviderIsBlocked()) {
            $this->createFailedRegisterRequest();
            return ['status' => 'failed', 'message' => $this->message];
        }
        //Check if the user has reached the maximum number of registration attempts
        if ($this->checkRegisterAttemptsWithIp()) {
            $this->createFailedRegisterRequest();
            return ['status' => 'failed', 'message' => $this->message];
        }
        //Create Register Request
        RegisterRequest::create([
            'email' => $this->email ?? null,
            'username' => $this->username ?? null,
            'ip' => $this->ip,
            'is_failed' => false,
        ]);


        User::create([
            'name' => $this->request->name,
            'surname' => $this->request->surname,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->request->password),
        ]);

        return ['status' => 'success', 'message' => 'Registration successfully completed.'];


    }

}
