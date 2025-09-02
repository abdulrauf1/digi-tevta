<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TraineeController extends Controller
{
    //
    function index() {

        dd("trainee dashboard");
        return view('admin.dashboard');
    }
}
