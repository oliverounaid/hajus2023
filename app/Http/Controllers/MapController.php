<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marker;
use Inertia\Inertia;

class MapController extends Controller
{
    public function index()
    {
        $markers = Marker::all()->toArray();
        return Inertia::render('Map', [
            'markers' => $markers,
        ])->withViewData(['markers' => $markers]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'desc' => 'nullable',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $marker = new Marker();
        $marker->name = $validatedData['name'];
        $marker->description = $validatedData['desc'];
        $marker->latitude = $validatedData['lat'];
        $marker->longitude = $validatedData['lng'];
        $marker->save();


        return response()->json([
            'message' => 'Marker saved successfully',
        ]);
    }
}