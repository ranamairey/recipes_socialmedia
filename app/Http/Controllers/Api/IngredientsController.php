<?php

namespace App\Http\Controllers\Api;

use App\Models\Ingredients;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\IngredientsResource;
use App\Http\Resources\IngredientsCollection;
use App\Http\Requests\IngredientsStoreRequest;
use App\Http\Requests\IngredientsUpdateRequest;

class IngredientsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', Ingredients::class);

        $search = $request->get('search', '');

        $allIngredients = Ingredients::search($search)
            ->latest()
            ->paginate();

        return new IngredientsCollection($allIngredients);
    }

    public function store(IngredientsStoreRequest $request)
    {

    // validate the input data
    $validatedData = $request->validate([
        'name' => 'required|max:255',
    ]);

    // create a new ingredient
    $ingredient = Ingredients::create([
        'name' => $validatedData['name'],
    ]);

    // redirect to the ingredients list with a success message

    return response()->json(['message' => 'success', 'ingredient' => $ingredient], 201);
}



    public function show(
        Request $request,
        Ingredients $ingredients
    ): IngredientsResource {
        $this->authorize('view', $ingredients);

        return new IngredientsResource($ingredients);
    }

    public function update(
        IngredientsUpdateRequest $request,
        Ingredients $ingredients
    ): IngredientsResource {
        $this->authorize('update', $ingredients);

        $validated = $request->validated();

        $ingredients->update($validated);

        return new IngredientsResource($ingredients);
    }

    public function destroy(
        Request $request,
        Ingredients $ingredients
    ): Response {
        $this->authorize('delete', $ingredients);

        $ingredients->delete();

        return response()->noContent();
    }
}
