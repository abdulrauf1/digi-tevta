<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show($filename)
    {
        // Check if the file exists in private storage
        // if (!Storage::disk('private')->exists($filename)) {
        //     abort(404);
        // }

        // Get the file path
        $path = Storage::disk('public')->path($filename);
        
        // Return the file as a response
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"'
        ]);
    }
}