<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(\App\Http\Requests\Auth\EmailVerificationRequest $request): RedirectResponse
    {

        $id = $request->route('id');
        $user = User::find($id);

        if (!$user) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=0')->with('error', 'Invalid request');
        }

        if (!$user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
        }

        return $this->redirect($user);
    }

    protected function redirect($user)
    {
        $dealId = request()->get('deal', request()->route('deal'));

        if (!$user->password) {
            $pbm = new PasswordBrokerManager(app());

            $token = $pbm->broker()->createToken($user);

            $params = [
                'token' => $token,
                'email' => $user->email,
            ];

            if ($dealId) {
                $params['deal'] = $dealId;
            }

            return redirect()->route('password.reset', $params)
                ->with('warning', 'You have not set password.');
        }

        if ($dealId) {
            $token = Crypt::encryptString($dealId);
            auth()->login($user);
            return redirect()->route('contract', $token)
                ->with('status', 'Email verified successfully.');
        }

        return redirect()->route('login', ['verified' => 1])
            ->with('status', 'Email verified successfully.');
        //return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
