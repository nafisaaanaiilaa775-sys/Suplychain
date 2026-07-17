<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortController extends Controller
{
    /**
     * Get list of ports, filtered by country to keep load light.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Port::query();

        // Filter by country code or ID if provided
        if ($request->has('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        } elseif ($request->has('country_code')) {
            $country = Country::where('iso2', strtolower($request->input('country_code')))->first();
            if ($country) {
                $query->where('country_id', $country->id);
            } else {
                return response()->json([]);
            }
        } else {
            // Default to empty array if no filter is provided to prevent loading too many markers initially
            return response()->json([]);
        }

        $ports = $query->with('country:id,name,iso2')->get();
        return response()->json($ports);
    }

    /**
     * Search ports by name.
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $ports = Port::where('name', 'like', "%{$q}%")
            ->orWhere('code', 'like', "%{$q}%")
            ->with('country:id,name,iso2')
            ->limit(15)
            ->get();

        return response()->json($ports);
    }
}
