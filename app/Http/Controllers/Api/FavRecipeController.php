<?php

namespace App\Http\Controllers\Api;

use App\Models\FavRecipe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\FavRecipeStoreRequest;
use App\Http\Requests\FavRecipeUpdateRequest;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavRecipeController extends Controller
{
    public function index(Request $request)
    {
        $favoret = [
            'favoret' => FavRecipe::query()->get()->where( 'user_id' , Auth::id()),
        ];
        
        return response()->json($favoret,status: Response::HTTP_OK);
    }

    public function store(Request $request,Recipe $recipe)
    {

        if($recipe->favRecipes()->where('user_id' , Auth::id())->where('recipe_id',$recipe->id)->exists())
        {
            $recipe->favRecipes()->where('recipe_id',$recipe->id)->delete();
            $masg = 'Unfavoret';
            return response()->json($masg,status: Response::HTTP_OK);
        }
        else{
            $fav = FavRecipe::query()->create([
                'user_id' => Auth::id(),
                'recipe_id' => $recipe->id,
            ]);
        }
        return response()->json($fav,status: Response::HTTP_OK);
    }

    public function show(
        Request $request,
        FavRecipe $favRecipe
    ) {
    }

    public function update(
        FavRecipeUpdateRequest $request,
        FavRecipe $favRecipe
    ) {
    }

    public function destroy(Request $request, FavRecipe $favRecipe)
    {
    }
}
