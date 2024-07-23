<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Deals;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class ContractController extends Controller
{

    public function index(Request $request, $token_or_id = null)
    {

        $apiSuplliers = Suppliers::apiSuppliers();
        $pricechange = [];
        foreach ($apiSuplliers as $k => $v) {
            $plans = collect($v);
            $pricechange[$k] = [
                'gas' => $plans->where('Utility', 'Gas')->count(),
                'electric' => $plans->where('Utility', 'Electric')->count()
            ];
        }

        $message = null;
        $deal = null;

        if ($token_or_id) {
            try {
                if (strlen($token_or_id) > 30) {
                    $decrypted = Crypt::decryptString($token_or_id);
                    list($id, $email) = array_pad(explode(',', $decrypted), 2, null);

                    $deal = Deals::with('supplier')->find($id);

                    if ($email) {
                        $verified = $this->verifyEmail($email, $deal);
                        if ($verified && $deal->user_id != $verified['user']->id) {
                            $message = $verified['message'];
                            $deal->user_id = $verified['user']->id;
                            $deal->save();
                        }
                    }
                } else {
                    $deal = Deals::with('supplier')->where('id', $token_or_id)->first();
                }
            } catch (DecryptException $e) {
            }
        }

        return Inertia::render('PCW', [
            'message' => $message,
            'deal' => $deal,
            'loggedin' => auth()->check(),
            'pricechange' => $pricechange
        ]);
    }

    /**
     * @param $email
     * @param $deal
     * @return array|false
     */
    protected function verifyEmail($email, $deal): bool|array
    {
        try {
            if ($email && $deal) {
                $user = User::firstOrNew(['email' => $email]);
                if ($user->id) {
                    $message = 'Email already verified';
                } else {
                    $user->first_name = data_get($deal, 'customer.firstName');
                    $user->last_name = data_get($deal, 'customer.lastName');
                    $user->phone = data_get($deal, 'customer.phone');
                    $user->save();
                    $message = 'Your email successfully verified.';
                }

                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                Auth::login($user);

                return ['message' => $message, 'user' => $user];
            }
        } catch (DecryptException $e) {
        }

        return false;
    }
}
