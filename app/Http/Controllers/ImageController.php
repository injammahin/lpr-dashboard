<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function show()
    {
        $path = request('path');

        if (!file_exists($path)) {
            return abort(404, 'Image not found');
        }

        return Response::file($path);
    }
}
