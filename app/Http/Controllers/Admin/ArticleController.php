<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesListApiResource;
use App\Models\Article;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Adapters\Phpunit\Support\ResultReflection;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::query();

        if (request()->has('title')) {
            $keyword = request()->has('title');
            $articles = $articles->where('title', 'like', "%$keyword%");
        }

        $articles = $articles->paginate();

        return response()->json([
            'articles' => ArticlesListApiResource::collection($articles)
        ]);
    }
}
