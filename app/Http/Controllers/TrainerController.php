<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainerController extends Controller
{
    //
    function index() {

        dd("trainer dashboard");
        return view('admin.dashboard');
    }
}
