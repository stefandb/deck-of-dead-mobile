<?php

namespace App\Http\Controllers;

use App\Models\Preset;
use Illuminate\View\View;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Events\Alert\ButtonPressed;

class HomeController extends Controller
{
    public function index(): View
    {
        $presets = Preset::latest()->get();

        return view('home', compact('presets'));
    }
}
