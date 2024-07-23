<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\GeneralMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DocusignController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        Mail::to('mubashar.mtp@gmail.com')->send(new GeneralMail("DocuSign WebHook",json_encode($request->all())));
    }
}
