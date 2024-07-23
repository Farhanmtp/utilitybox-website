<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Models\Messages;
use App\Notifications\Admin\BookNowNotification;
use App\Notifications\Admin\ContactFormNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\File;

/**
 * @group Forms
 *
 * @unauthenticated
 */
class ContactController extends ApiController
{

    /**
     * Submit Contact
     *
     * Submit contact form data
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function contactForm(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['nullable', 'string'],
            'email' => ['required', 'string', 'email'],
            'phone' => ['nullable', 'string'],
            'message' => ['required', 'string'],
            'attachment' => [
                'nullable',
                File::types(['jpg', 'jpeg', 'png', 'bmp', 'pdf'])
                    ->min('10kb')->max('3mb')
            ],
        ]);

        $message = new Messages();
        $message->type = $request->get('type', 'contact');
        $message->first_name = $request->get('first_name');
        $message->last_name = $request->get('last_name');
        $message->email = $request->get('email');
        $message->phone = $request->get('phone');
        $message->subject = $request->get('subject');
        $message->message = $request->get('message');

        $message->save();
        if ($message->id && $request->hasFile('attachment')) {
            $attachment = $request->file('attachment');

            $fileName = $attachment->getClientOriginalName();

            $upload = $attachment->move(storage()->path('forms'), $fileName);
            if ($upload) {
                $message->attachment = $fileName;
                $message->save();
            }
        }

        $email = settings('app.notification-email');

        Notification::route('mail', $email)->notify(new ContactFormNotification($request));

        return $this->successResponse('Form submit successfully.');
    }

    /**
     * Book Now
     *
     * Submit book now form data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookNow(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'business_name' => 'required',
        ]);

        $to = settings('app.notification-email');

        Notification::route('mail', $to)->notify(new BookNowNotification($request));

        return $this->successResponse('Form submit successfully.');
    }
}
