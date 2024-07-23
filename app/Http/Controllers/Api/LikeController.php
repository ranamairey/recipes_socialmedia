<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\LikeResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeCollection;
use App\Http\Requests\LikeStoreRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LikeUpdateRequest;
use App\Models\Recipe;

class LikeController extends Controller
{
    public function index(Request $request,Recipe $recipe)
    {
        $countlike = $recipe->likes()->count();
        $like = [
            'like' => $countlike
        ];
        return response()->json($like, status: Response::HTTP_OK);
    }

    public function store(Request $request,Recipe $recipe)
    {
        if($recipe->likes()->where('user_id',Auth::id())->exists())
        {
            $recipe->likes()->where('user_id',Auth::id())->delete();
            $masg = 'Unlike';
            return response()->json($masg,status: Response::HTTP_OK);
        }
        else{
            $recipe = Like::query()->create([
                'user_id' => Auth::id(),
                'recipe_id' => $recipe->id,
            ]);
        }
        return response()->json($recipe,status: Response::HTTP_OK);
    }

    public function show(Request $request, Like $like)
    {
        //
    }

    public function update(LikeUpdateRequest $request, Like $like)
    {
        //
    }

    public function destroy(Request $request, Like $like)
    {
        //
    }
}
