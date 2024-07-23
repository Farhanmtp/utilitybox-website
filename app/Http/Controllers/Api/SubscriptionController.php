<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use Illuminate\Http\Request;

/**
 * @group Subscription
 *
 * @unauthenticated
 */
class SubscriptionController extends ApiController
{

    /**
     * Subscribe
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'first_name' => ['required', 'string'],
            'last_name' => ['nullable', 'string'],
        ]);

        $subscription = new Subscription();
        $subscription->first_name = $request->get('first_name');
        $subscription->last_name = $request->get('last_name');
        $subscription->email = $request->get('email');

        $subscription->save();
        if ($subscription->id) {
            return $this->successResponse($subscription, 'Subscribed successfully.');
        } else {
            return $this->errorResponse('Not subscribed successfully', 200);
        }
    }

    /**
     * Unsubscribe
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email']
        ]);

        $subscription = Subscription::where('email', $request->get('email'));

        $subscription->delete();

        return $this->successResponse('Unsubscribed successfully.');
    }
}
