<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()->get();
        $barbers  = Barber::active()->get();

        return view('home.index', compact('services', 'barbers'));
    }
}
