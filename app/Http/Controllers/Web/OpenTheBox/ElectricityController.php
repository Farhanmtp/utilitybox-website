<?php

namespace App\Http\Controllers\Web\OpenTheBox;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ElectricityController extends Controller
{


    public function index()
    {

        return Inertia::render('openTheBox/Electricity', []);
    }
}
