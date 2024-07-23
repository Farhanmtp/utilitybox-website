<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class TermsAndConditions extends Controller
{


    public function index(){

        return Inertia::render('TermsAndConditions', []);
    }
}
