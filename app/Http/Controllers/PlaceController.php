<?php

namespace App\Http\Controllers;

use App\Place;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;

class PlaceController extends Controller
{
    public function createPlace(Request $request)
    {
        $this->validate($request, [
            'city_id' => 'required',
            'category_id' => 'required',
            'name' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();

        $place = new Place();
        $place->city_id = $request->input('city_id');
        $place->category_id = $request->input('category_id');
        $place->name = $request->input('name');
        $place->description = $request->input('description', null);
        $place->lat = $request->input('lat');
        $place->lng = $request->input('lng');

        if (in_array($user->account_type, [User::TYPE_ADMINISTRATOR, User::TYPE_MODERATOR])) {
            $place->is_accepted = 1;
        } else {
            $place->is_accepted = 0;
        }

        $place->save();

        return response()->json([
            'place' => $place
        ], 201);
    }

    public function moderatePlace(Request $request, $id)
    {
        $this->validate($request, [
            'is_accepted' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();

        if ($user->account_type === User::TYPE_USER) {
            return response()->json([
                'message' => 'Access is forbidden!'
            ], 401);
        }

        $place = Place::find($id);

        if (!$place) {
            return response()->json([
                'message' => 'Place not found!'
            ], 404);
        }

        $place->is_accepted = $request->input('is_accepted');

        foreach ($request->except(['token', 'is_accepted']) as $inputField => $value) {
            $place->$inputField = $value;
        }

        $place->save();

        return response()->json([
            'place' => $place
        ], 200);
    }

    public function getPlacesForCategoryAndCity(Request $request)
    {
        $this->validate($request, [
            'city_id' => 'required',
            'category_id' => 'required'
        ]);

        $places = Place::select('id', 'name', 'description', 'lat', 'lng')
            ->where('city_id', $request->input('city_id'))
            ->where('category_id', $request->input('category_id'))
            ->where('is_accepted', 1)
            ->get()
            ->toArray();

        return response()->json([
            'places' => $places
        ], 200);
    }

    public function getInactivePlaces(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();

        if ($user->account_type === User::TYPE_USER) {
            return response()->json([
                'message' => 'Access is forbidden!'
            ], 401);
        }

        $places = Place::select('id', 'name', 'description', 'category_id', 'city_id', 'lat', 'lng')
            ->where('is_accepted', 0)
            ->get()
            ->toArray();

        return response()->json([
            'places' => $places
        ], 200);
    }
}