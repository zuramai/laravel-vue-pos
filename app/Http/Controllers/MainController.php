<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        return view('layouts.app');
    }

    public function auth() {
        return view('layouts.auth');
    }
}
