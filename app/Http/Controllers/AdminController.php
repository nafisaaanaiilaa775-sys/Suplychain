<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Helper to get current user.
     */
    private function getUser()
    {
        return Auth::user() ?? User::where('email', 'guest@riskintel.local')->first();
    }

    /**
     * Get summary data for admin panel.
     */
    public function dashboard(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'created_at')->get();
        $portsCount = Port::count();
        $articles = Article::with('author:id,name')->get();
        $countriesCount = Country::count();

        return response()->json([
            'users' => $users,
            'ports_count' => $portsCount,
            'countries_count' => $countriesCount,
            'articles' => $articles
        ]);
    }

    /**
     * Save new/updated port.
     */
    public function storePort(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:ports,code,' . $request->input('id'),
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'country_id' => 'required|exists:countries,id'
        ]);

        $port = Port::updateOrCreate(
            ['id' => $request->input('id')],
            $request->only(['name', 'code', 'latitude', 'longitude', 'country_id'])
        );

        return response()->json([
            'message' => 'Port saved successfully',
            'port' => $port->load('country')
        ]);
    }

    /**
     * Delete port.
     */
    public function destroyPort(int $id): JsonResponse
    {
        Port::destroy($id);
        return response()->json(['message' => 'Port deleted successfully']);
    }

    /**
     * Save analysis article.
     */
    public function storeArticle(Request $request): JsonResponse
    {
        $user = $this->getUser();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string'
        ]);

        $article = Article::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'category' => $request->input('category'),
                'author_id' => $user->id,
                'image_url' => $request->input('image_url')
            ]
        );

        return response()->json([
            'message' => 'Article saved successfully',
            'article' => $article->load('author')
        ]);
    }

    /**
     * Delete article.
     */
    public function destroyArticle(int $id): JsonResponse
    {
        Article::destroy($id);
        return response()->json(['message' => 'Article deleted successfully']);
    }
}
