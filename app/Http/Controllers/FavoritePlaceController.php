<?php

namespace App\Http\Controllers;

use App\FavoritePlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class FavoritePlaceController extends Controller
{
    public function toggleFavoritePlace(Request $request)
    {
        $this->validate($request, [
            'place_id' => 'required'
        ]);

        $user = JWTAuth::parseToken()->toUser();

        $place = FavoritePlace::select('id')
            ->where('place_id', $request->input('place_id'))
            ->where('user_id', $user->id)
            ->get()
            ->toArray();

        if (count($place) === 0) {
            $favoritePlace = new FavoritePlace();
            $favoritePlace->user_id = $user->id;
            $favoritePlace->place_id = $request->input('place_id');

            $favoritePlace->save();

            return response()->json([
                'place' => $favoritePlace
            ], 201);
        }

        DB::table('favorite_places')
            ->where('place_id', $request->input('place_id'))
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'place' => null
        ], 201);
    }

    public function isFavoritePlace(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->toUser();

        $place = FavoritePlace::select('id')
            ->where('place_id', $id)
            ->where('user_id', $user->id)
            ->get()
            ->first();

        if ($place) {
            $placeId = $place->id;
        } else {
            $placeId = null;
        }

        return response()->json([
            'id' => $placeId
        ], 201);
    }

    public function getFavoritePlacesForAccount(Request $request)
    {
        $user = JWTAuth::parseToken()->toUser();

        $favoritePlaces = DB::table('favorite_places')
            ->leftJoin('places', 'favorite_places.place_id', '=', 'places.id')
            ->select('places.id', 'places.name', 'places.description', 'places.lat', 'places.lng', 'places.city_id', 'places.category_id')
            ->where('favorite_places.user_id', $user->id)
            ->get()
            ->toArray();

        return response()->json([
            'places' => $favoritePlaces
        ], 200);
    }

}