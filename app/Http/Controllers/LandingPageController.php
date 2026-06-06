<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Change this line from 'welcome' to 'home'
        return view('home'); // This maps to resources/views/home.blade.php
    }
}