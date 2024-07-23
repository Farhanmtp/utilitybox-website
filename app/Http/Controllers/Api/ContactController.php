<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Mail\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;

class ContactController extends ApiController
{

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitForm(Request $request)
    {
        $request->validate([
            'firstName' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'message' => ['required', 'string'],
        ]);

        $sent = Mail::to('mubashar.ahmad@mtp.tech')->send(new ContactForm($request->all()));

        if ($sent instanceof SentMessage) {
            return response()->json(['success' => true, 'message' => 'Form submit successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Form not submit successfully.']);
        }
    }
}
