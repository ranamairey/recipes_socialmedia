<?php

namespace App\Http\Controllers\Api;

use App\Models\Rate;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\RateCollection;
use App\Http\Requests\RateStoreRequest;
use App\Http\Requests\RateUpdateRequest;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function index(Recipe $recipe, Request $request)
    {
        $argv = $recipe->rates()->avg('number');
        $rate = [
            'rate' => $argv
        ];
        return response()->json($rate, status: Response::HTTP_OK);
    }

    public function store(RateStoreRequest $request, Recipe $recipe)
    {
        if ($recipe->rates()->where('user_id', Auth::id())->exists()) {
            $rate = $recipe->rates()->where('user_id', Auth::id());
            $rate->update([
                'number' => $request->number,
            ]);
            $result = $rate->first(); // النتيجة هنا تكون القيمة المحدثة للتقييم
            return response()->json($result, Response::HTTP_ACCEPTED);
        } else {
            $rate = Rate::query()->create([
                'user_id' => auth()->user()->id,
                'recipe_id' => $recipe->id,
                'number' => $request->number,
            ]);
            $result = $rate->fresh(); // النتيجة هنا تكون القيمة الجديدة للتقييم المنشأ
            return response()->json($result, Response::HTTP_CREATED);
        }
    }

    public function show(Request $request, Recipe $recipe)
    {

        // $rate = $recipe->rates()->find($request->id);

        // return response()->json($rate, status: Response::HTTP_CREATED);

    }

    public function update(RateUpdateRequest $request, Recipe $recipe)
    {
        //
    }

    public function destroy(Request $request, Rate $rate, Recipe $recipe)
    {
        $rate = $recipe->rates()->where('user_id', Auth::id());

        if ($rate->exists()) {
            $deletedRate = $rate->first(); // السجل المحذوف
            $rate->delete();
            return response()->json($deletedRate, Response::HTTP_ACCEPTED);
        } else {
            return response()->json("no rate", Response::HTTP_FORBIDDEN);
        }
    }
}
