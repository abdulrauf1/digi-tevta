<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    function index() {

        dd("admin dashboard");
        return view('admin.dashboard');
    }
}
