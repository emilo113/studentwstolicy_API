<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function addCity(Request $request)
    {
        $city = new City();
        $city->name = $request->input('name');
        $city->lat = $request->input('lat');
        $city->lng = $request->input('lng');
        $city->save();

        return response()->json([
            'city' => $city
        ], 201);
    }

    public function getCities(Request $request)
    {
        return response()->json([
            'cities' => City::all([
                'id', 'name', 'lat', 'lng'
            ])
        ]);
    }
}