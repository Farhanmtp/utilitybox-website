<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Mail\GeneralMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DocusignController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        Mail::to('mubashar.mtp@gmail.com')->send(new GeneralMail("DocuSign WebHook", json_encode($request->all())));
    }
}
