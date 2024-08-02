<?php

namespace dorukyy\loginx;

use App\Models\User;
use dorukyy\loginx\Http\Requests\LoginRequest;
use dorukyy\loginx\Models\BlockedIp;
use dorukyy\loginx\Models\FailedLogin;
use dorukyy\loginx\Models\Login;
use dorukyy\loginx\Models\Timeout;
use Illuminate\Support\Facades\Auth;

class LoginHandler
{
    public string $ipAddress;

    public string $foundType;
    public ?User $user;
    public string $userInput;
    public LoginRequest $request;

    public int $status;
    public string $message;

    public function __construct($request)
    {
        $this->ipAddress = $request->ip();
        $this->user = LoginxFacade::getUser($request->user_input)['user'] ?? null;
        $this->foundType = LoginxFacade::getUser($request->user_input)['foundType'] ?? null;
        $this->userInput = $request->user_input;
        $this->request = $request;

    }

    private function isIpBlocked(): bool
    {
        $blockedIp = BlockedIp::where('ip', $this->ipAddress)->where('blocked_until', '>=', now())->first();
        if ($blockedIp) {
            $this->message = str_replace('{blocked_until}', $blockedIp->blocked_until,
                config('loginx.ip_blocked_message'));
            return true;
        }
        return false;
    }

    private function checkFailedLoginsWithIp(): bool
    {
        $failedLogins = FailedLogin::where('ip', $this->ipAddress)
            ->where('created_at', '>=', now()->subSeconds(LoginxFacade::getTimeOutInSecs()))
            ->get();

        if ($failedLogins->count() >= LoginxFacade::getTimeOutAfterAttemptCount()) {
            $timeOuts = Timeout::where('ip', $this->ipAddress)->where('created_at', '>=', now()->subSeconds
            (LoginxFacade::getTimeOutDuration()))->get();
            if ($this->checkTimeOut()) {
                return true;
            }
            $timeOut = Timeout::create([
                'ip' => $this->ipAddress,
                'user_id' => $this->user->id ?? null,
                'unblocked_at' => now()->addSeconds(LoginxFacade::getTimeOutDuration())->toDateTimeString(),
            ]);
            $this->message = config('loginx.max_failed_login_ip_message');
            return true;
        }

        return false;

    }

    private function checkFailedLoginsWithUser(): bool
    {
        if (!isset($this->user)) {
            return false;
        }
        $failedLogins = FailedLogin::where('user_id', $this->user->id)
            ->where('created_at', '>=', now()->subSeconds(LoginxFacade::getTimeOutInSecs()))
            ->get();

        if ($failedLogins->count() >= LoginxFacade::getTimeOutAfterAttemptCount()) {
            Timeout::create([
                'ip' => $this->ipAddress,
                'user_id' => $this->user->id ?? null,
                'unblocked_at' => now()->addSeconds(LoginxFacade::getTimeOutInSecs())->toDateTimeString(),
            ]);
            $this->message = config('loginx.max_failed_login_account_message');
            return true;
        }

        return false;

    }

    private function checkAccountBlock(): bool
    {
        if (LoginxFacade::isUsersBlockable()) {
            $blockedUser = User::where('id', $this->user->id)->where('blocked_at', '!=', null)
                ->first();
            if ($blockedUser) {
                if ($blockedUser->blocked_until) {
                    if($blockedUser->blocked_until < now()){
                        return false;
                    }
                    $this->message = str_replace('{blocked_until}', $blockedUser->blocked_until,
                        config('loginx.account_unblocked_at_message'));
                } else {
                    $this->message = config('loginx.account_blocked_message');
                }
                return true;
            }
        }
        return false;
    }

    public function autoBlockIp(): void
    {
        BlockedIp::create(
            [
                'reason' => 'Auto blocked due to failed login attempts.',
                'blocked_by' => 'Loginx',
                'ip' => $this->ipAddress,
                'blocked_until' => now()->addMinutes(intval(LoginxFacade::getAutoBlockDuration()))->toDateTimeString(),
            ]
        );
    }

    public function login(): array
    {
        if ($this->checkFailedLoginsToDifferentUsers()) {
            return ['status' => 'failed', 'message' => $this->message];
        }
        if ($this->isIpBlocked()) {
            return ['status' => 'failed', 'message' => $this->message];
        }
        if ($this->checkTimeOut()) {
            return ['status' => 'failed', 'message' => $this->message];
        }
        if ($this->checkFailedLoginsWithIp()) {
            return ['status' => 'failed', 'message' => $this->message];
        }
        if ($this->checkFailedLoginsWithUser()) {
            return ['status' => 'failed', 'message' => $this->message];
        }


        if ($this->user) {
            if (password_verify($this->request->password, $this->user->password)) {
                $credentials = [
                    'password' => $this->request->password,
                ];
                if ($this->foundType == 'email') {
                    $credentials['email'] = $this->userInput;
                } elseif ($this->foundType == 'username') {
                    $credentials['username'] = $this->userInput;
                } elseif ($this->foundType == 'phone') {
                    $credentials['phone'] = $this->userInput;
                }
                if (Auth::attempt($credentials, $this->request->filled('remember'))) {
                    if ($this->checkAccountBlock()) {
                        return ['status' => 'failed', 'message' => $this->message];
                    }
                    Login::create([
                        'ip' => $this->ipAddress,
                        'user_id' => $this->user->id,
                        'user_agent' => $this->request->userAgent(),
                        'headers' => json_encode($this->request->header()),
                        'user_input' => $this->userInput,
                        'found_type' => $this->foundType
                    ]);
                    return [
                        'status' => 'success', 'message' => 'You have successfully logged in.', 'user' => $this->user
                    ];

                }

            } else {
                FailedLogin::create([
                    'ip' => $this->ipAddress,
                    'user_input' => $this->userInput,
                    'is_found' => true,
                    'found_type' => $this->foundType,
                    'user_id' => $this->user->id
                ]);
                $this->message = 'The password is incorrect.';
                return ['status' => 'failed', 'message' => $this->message];

            }
        } else {
            FailedLogin::create([
                'ip' => $this->ipAddress,
                'user_input' => $this->userInput,
                'is_found' => false,
                'found_type' => null,
                'user_id' => null
            ]);
            $this->message = 'The user is not found.';
            return ['status' => 'failed', 'message' => $this->message];

        }
        return ['status' => 'failed', 'message' => 'An error occurred. Please try again later.'];
    }

    private function checkFailedLoginsToDifferentUsers(): bool
    {
        $failedLogins = FailedLogin::where('ip', $this->ipAddress)
            ->where('created_at', '>=', now()->subSeconds(LoginxFacade::getAutoBlockIpInSecs()))
            ->selectRaw('COALESCE(user_id, "null") as user_id_group')
            ->groupBy('user_id_group')
            ->get();


        if ($failedLogins->count() >= LoginxFacade::getAutoBlockFailedAttemptNumber()) {
            $this->autoBlockIp();
            $this->message = config('loginx.ip_blocked_now_message');

            return true;

        }
        return false;

    }

    private function checkTimeOut(): bool
    {
        $timeOut = Timeout::where('ip', $this->ipAddress)->where('unblocked_at', '>=', now())->first();
        if ($this->user) {
            $timeOutAccount = Timeout::where('user_id', $this->user->id)->where('unblocked_at', '>=',
                now())->first() ?? null;
            if ($timeOutAccount) {
                $this->message = str_replace('{unblocked_at}', $timeOutAccount->unblocked_at,
                    config('loginx.account_timeout_message'));
                return true;
            }
        }


        if ($timeOut) {
            $this->message = str_replace('{unblocked_at}', $timeOut->unblocked_at,
                config('loginx.ip_timeout_message'));
            return true;
        }
        return false;

    }

}
