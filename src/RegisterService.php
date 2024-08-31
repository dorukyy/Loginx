<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Models\BlockedMailProvider;
use dorukyy\loginx\Models\RegisterRequest;
use dorukyy\loginx\Models\Timeout;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    private string $email;
    private ?string $username;
    private string $ipAddress;

    private Http\Requests\RegisterRequest $request;

    public function __construct(Http\Requests\RegisterRequest $request)
    {
        $this->email = $request->email ?? null;
        $this->username = $request->username ?? null;
        $this->ipAddress = $request->ip();
        $this->request = $request;
    }

    public function run(): array
    {
        //Check if the ip is blocked
        if (LoginxFacade::isIpBlocked($this->ipAddress)) {
            return ['success' => false, 'message' => config('loginx.messages.ipBlocked')];
        }
        //Check if the recaptcha is valid
        if (!LoginxFacade::verifyRecaptchaForType('REGISTER', $this->request['cf-turnstile-response'] ?? null)) {
            return ['success' => false, 'message' => config('loginx.messages.recaptchaFailed')];
        }
        //Check if the ip is timed out
        $ipTimeout = LoginxFacade::isIpTimeOut($this->ipAddress);
        if ($ipTimeout['timeout']) {
            return [
                'success' => false, 'message' => str_replace('{unblocked_at}', $ipTimeout['unblockedAt'],
                    config('loginx.messages.ipTimeout'))
            ];
        }
        //Check if the mail provider is blocked
        if ($this->isMailProviderBlocked()) {
            $this->createRegisterRequest();
            return ['success' => false, 'message' => config('loginx.mailProviderBlocked')];
        }
        //Check if the user has reached the maximum number of registration attempts
        if ($this->checkRegisterAttemptsWithIp()) {
            $this->createRegisterRequest();
            return ['success' => false, 'message' => config('loginx.maxFailedRegisterIp')];
        }
        //Create Register Request
        $this->createRegisterRequest(false);

        $referralCode = $this->request->query('referral_code');

        $referrer = User::where('referral_code', $referralCode)?->first()?->id ?? null;

        $userData = [
            'name' => $this->request->name ?? null,
            'surname' => $this->request->surname ?? null,
            'email' => $this->email ?? null,
            'username' => $this->username ?? null,
            'password' => Hash::make($this->request->password),
            'referral_code' => $referrer];

        if (SettingsFacade::getIsEmailActivation()) {
            $user = User::create($userData);

            $this->sendEmailVerification($user);
        } else {
            $userData['email_verified_at'] = now();
            User::create($userData);
        }


        return ['success' => true, 'message' => config('loginx.messages.registerSuccess')];

    }

    private function sendEmailVerification(User $user): void
    {
        $user->sendEmailVerificationMail();
    }

    /*
     * Check if the mail provider is in the list of blocked mail providers
     */
    private function isMailProviderBlocked(): bool
    {
        $emailProvider = explode('@', $this->email)[1];
        return BlockedMailProvider::where('url', $emailProvider)->exists();
    }

    /*
     * Create a register request
     */
    private function createRegisterRequest(bool $isFailed = true): void
    {
        RegisterRequest::create([
            'email' => $this->email ?? null,
            'username' => $this->username ?? null,
            'ip' => $this->ipAddress,
            'is_failed' => $isFailed,
        ]);
    }

    /*
     * Check if the user has reached the maximum number of registration attempts
     * If the user has reached the maximum number of registration attempts, Set a timeout for the ip address
     */
    private function checkRegisterAttemptsWithIp(): bool
    {
        $registerRequests = RegisterRequest::where('ip', $this->ipAddress)->get();
        $maxRegisterAttempts = SettingsFacade::getMaxRegisterFailedAttempts();
        if ($registerRequests->count() > $maxRegisterAttempts) {
            Timeout::create([
                'ip' => $this->ipAddress,
                'unblocked_at' => now()->addSeconds(SettingsFacade::getRegisterTimeoutDuration()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return true;
        }
        return false;
    }

}
