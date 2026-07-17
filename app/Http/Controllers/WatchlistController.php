<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    /**
     * Get the authenticated user (or fallback to seeded guest user).
     */
    private function getUser()
    {
        return Auth::user() ?? User::where('email', 'guest@riskintel.local')->first();
    }

    /**
     * Get user watchlist.
     */
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $watchlist = Watchlist::where('user_id', $user->id)
            ->with(['country' => function ($q) {
                $q->select('id', 'name', 'iso2', 'currency_code', 'gdp', 'inflation');
            }])
            ->get();

        return response()->json($watchlist);
    }

    /**
     * Add country to watchlist.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $countryId = $request->input('country_id');

        $item = Watchlist::updateOrCreate([
            'user_id' => $user->id,
            'country_id' => $countryId
        ]);

        $item->load('country');

        return response()->json([
            'message' => 'Country added to watchlist',
            'data' => $item
        ]);
    }

    /**
     * Remove country from watchlist.
     */
    public function destroy(int $countryId): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Watchlist::where('user_id', $user->id)
            ->where('country_id', $countryId)
            ->delete();

        return response()->json(['message' => 'Country removed from watchlist']);
    }
}
