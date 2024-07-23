<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompareController extends Controller
{

    public function index()
    {

        return Inertia::render('PCW', []);
    }
}
