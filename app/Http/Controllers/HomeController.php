<?php

namespace App\Http\Controllers;

use App\Models\Preset;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $presets = Preset::latest()->get();

        return view('home', compact('presets'));
    }
}
