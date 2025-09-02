<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    //
    function index() {

        dd("Clerk dashboard");
        return view('admin.dashboard');
    }
}
