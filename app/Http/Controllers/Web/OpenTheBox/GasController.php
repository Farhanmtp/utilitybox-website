<?php

namespace App\Http\Controllers\Web\OpenTheBox;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class GasController extends Controller
{

    public function index()
    {

        return Inertia::render('openTheBox/Gas', []);
    }
}
