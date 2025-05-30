<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        // Retornar todas las obras
        return Gallery::all();
    }

    public function show($id)
    {
        // Retornar una obra específica por ID
        return Gallery::findOrFail($id);
    }
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->update($request->only([
            'name',
            'description',
            'url01',
            'url02',
            'url03',
            'url04'
        ]));
        return $gallery;
    }
    

}
