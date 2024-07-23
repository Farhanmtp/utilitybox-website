<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{

    public function index()
    {

        return Inertia::render('Contact', []);
    }

    public function bookNow(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'business_name' => 'required',
        ]);

        $email = settings('app.notification-email');

        $sent = Mail::to($email)->send(new ContactForm($request->all()));

        if ($sent instanceof SentMessage) {
            return response()->json(['success' => true, 'message' => 'Form submit successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Form not submit successfully.']);
        }
    }
}
