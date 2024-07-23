<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Step;
use App\Models\Ingredients;
use Illuminate\Support\Facades\Auth;
use App\Models\RecipeIngredients;
use App\Http\Controllers\Contr;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $productQuery = Recipe::query();
        $name = $request->query('name');
        $chefname = $request->query('chefname');
        $ingredients = $request->input('ingredients') ?? '';
        $category_name = $request->query('category_name');
        if ($category_name) {
            $aa = Category::query()
                ->where('name', $category_name)
                ->get()
                ->pluck('id');;

            $recipes = $productQuery->where('category_id', $aa)->get();
            return response()->json($recipes, Response::HTTP_OK);
        }
        if ($chefname) {
            $chef = User::query()
                ->where(function ($query) use ($chefname) {
                    $query->where('f_name', 'LIKE', '%' . $chefname . '%')
                        ->orWhere('l_name', 'LIKE', '%' . $chefname . '%');
                })
                ->get()
                ->pluck('id');
            $recipes = $productQuery->whereIn('user_id', $chef)->get();
            return response()->json($recipes, Response::HTTP_OK);
        }
        if ($name) {
            $productQuery->where('name', $name);
        }


        // if ($ingredients) {
        //     $aa = RecipeIngredients::query()
        //         ->whereIn('ingredients_id', $ingredients)
        //         ->get()
        //         ->pluck('recipe_id');

        //     $recipes = $productQuery->find($aa);

        //     return response()->json($recipes, Response::HTTP_OK);
        // }
        if ($ingredients) {
            $ingredients = explode(",", $ingredients);
            $recipeIds = RecipeIngredients::whereIn('ingredients_id', $ingredients)
                ->pluck('recipe_id')
                ->toArray();

            $recipes = $productQuery->whereIn('id', $recipeIds)->get();

            return response()->json($recipes, Response::HTTP_OK);
        }
        $product = $productQuery->get();
        return response()->json($product, status: Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'required|string',
            'tips' => 'nullable|string',
            'main_img_url' => 'required|file|mimes:png,jpg',
            'expected_cost' => 'nullable|numeric',
            'expected_time' => 'nullable|integer',
            'difficulty_level' => 'nullable|integer',
            'ingredients' => 'array|required',
            'ingredients.*.ingredient_id' => 'required|integer',
            'ingredients.*.quanttity' => 'required|integer',
            'ingredients.*.is_main_ingredient' => 'required|boolean',
            'ingredients.*.reduserCompany' => 'nullable|string',
            'steps' => 'array|required',
            'steps.*.content' => 'required|string',
            'steps.*.number' => 'required|integer',
            'steps.*.image_url' => 'required|file|mimes:png,jpg',
        ]);

        if ($request->hasFile('main_img_url')) {
            $file = $request->file('main_img_url');
            $path = $file->store('images/recipe');

            $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));

            $validatedData['main_img_url'] = $storedPath;
        }
        $recipe = Recipe::create([
            'category_id' => $validatedData['category_id'],
            'user_id' => auth()->user()->id,
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'tips' => $validatedData['tips'],
            'main_img_url' => $validatedData['main_img_url'],
            'views' => 0,
            'expected_cost' => $validatedData['expected_cost'],
            'expected_time' => $validatedData['expected_time'],
            'difficulty_level' => $validatedData['difficulty_level'],
        ]);

        foreach ($validatedData['ingredients'] as $ingredientData) {
            $ingredient = Ingredients::findOrFail($ingredientData['ingredient_id']);
            $recipeIngredient = new RecipeIngredients([
                'quanttity' => $ingredientData['quanttity'],
                'is_main_ingredient' => $ingredientData['is_main_ingredient'],
                'reduserCompany' => $ingredientData['reduserCompany'] ?? '',
            ]);
            $recipeIngredient->recipe()->associate($recipe);
            $recipeIngredient->ingredients()->associate($ingredient);
            $recipeIngredient->save();
        }

        foreach ($validatedData['steps'] as $index => $stepData) {

            if ($request->hasFile("steps.$index.image_url")) {
                $file = $request->file("steps.$index.image_url");
                $path = $file->store('images/step');

                $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));

                $stepData["steps.$index.image_url"] = $storedPath;
            }
            //return response()->json(['message' => 'success', 'Recipe' => $validatedData], 201);
            $step = new Step([
                'content' => $stepData['content'],
                'number' => $stepData['number'],
                'image_url' => $stepData["steps.$index.image_url"]
            ]);

            $step->recipe()->associate($recipe);
            $step->save();
        }

        return response()->json(['message' => 'success', 'Recipe' => $recipe], 201);
    }




    public function show($id)
    {
        $recipe = Recipe::with('steps', 'allRecipeIngredients')->findOrFail($id);
        $recipe->views += 1;
        $recipe->save();
        if ($recipe) {
            return response()->json(['recipe' => $recipe], 200);
        } else {
            return response()->json(['recipe' => 'recipe not found'], 404);
        };
    }


    public function update(Request $request, Recipe $recipe)
    {
        $validatedData = $request->validate([
            'category_id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'tips' => 'nullable|string',
            'main_img_url' => 'file|mimes:png,jpg',
            'expected_cost' => 'nullable|numeric',
            'expected_time' => 'nullable|integer',
            'difficulty_level' => 'nullable|integer',
            'ingredients' => 'array',
            'ingredients.*.id' => 'integer',
            'ingredients.*.ingredient_id' => 'integer',
            'ingredients.*.quanttity' => 'integer',
            'ingredients.*.is_main_ingredient' => 'boolean',
            'ingredients.*.reduserCompany' => 'nullable|string',
            'steps' => 'array',
            'steps.*.id' => 'integer',
            'steps.*.content' => 'string',
            'steps.*.number' => 'integer',
            'steps.*.image_url' => 'file|mimes:png,jpg',
        ]);
        unset($validatedData['views']);
        if ($request->hasFile('main_img_url')) {
            $fullPath = $recipe->main_img_url;
            $shortPath = str_replace("C:\\Users\\scc-asus\\Desktop\\Project2\\yam\\storage\\app/", "", $fullPath);
            Storage::delete($shortPath);

            $file = $request->file('main_img_url');
            $path = $file->store('images/recipe');

            $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));

            $validatedData['main_img_url'] = $storedPath;
        }
        $recipe->update($validatedData);
        if (isset($validatedData['ingredients'])) {
            foreach ($validatedData['ingredients'] as $ingredientData) {
                if (isset($ingredientData['id'])) {
                    $recipeIngredient = RecipeIngredients::findOrFail($ingredientData['id']);
                    $recipeIngredient->update([
                        'quanttity' => $ingredientData['quanttity'],
                        'is_main_ingredient' => $ingredientData['is_main_ingredient'],
                        'reduserCompany' => $ingredientData['reduserCompany'] ?? '',
                    ]);

                    if (isset($ingredientData['ingredient_id'])) {
                        $ingredient = Ingredients::findOrFail($ingredientData['ingredient_id']);
                        $recipeIngredient->ingredients()->associate($ingredient);
                        $recipeIngredient->save();
                    }
                } else {
                    $ingredient = Ingredients::findOrFail($ingredientData['ingredient_id']);
                    $recipeIngredient = new RecipeIngredients([
                        'quanttity' => $ingredientData['quanttity'],
                        'is_main_ingredient' => $ingredientData['is_main_ingredient'],
                        'reduserCompany' => $ingredientData['reduserCompany'] ?? '',
                    ]);
                    $recipeIngredient->recipe()->associate($recipe);
                    $recipeIngredient->ingredients()->associate($ingredient);
                    $recipeIngredient->save();
                }
            }
        }
        
        
           // التعامل مع الخطوات المرسلة في الطلب
    if (isset($validatedData['steps'])) {
        foreach ($validatedData['steps'] as $stepData) {
            if (isset($stepData['id'])) {
                // إذا كان هناك معرف للخطوة، قم بتحديثها
                $step = Step::findOrFail($stepData['id']);
                $step->update([
                    'content' => $stepData['content'],
                    'number' => $stepData['number'],
                ]);
                
                if ($request->hasFile('steps') && isset($stepData['image_url'])) {
                    // إذا تم رفع ملف صورة جديد للخطوة، قم بحذف الصورة القديمة واحفظ الصورة الجديدة
                    $fullPath = $step->image_url;
                    $shortPath = str_replace("C:\\Users\\scc-asus\\Desktop\\Project2\\yam\\storage\\app/", "", $fullPath);
                    Storage::delete($shortPath);
                    
                    $file = $request->file('steps')[$stepData['id']]['image_url'];
                    $path = $file->store('images/step');
                    
                    $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));
                    
                    $step->image_url = $storedPath;
                    $step->save();
                }
            } else {
                // إذا لم يكن هناك معرف للخطوة، قم بإنشاء خطوة جديدة وربطها بالوصفة
                $step = new Step([
                    'content' => $stepData['content'],
                    'number' => $stepData['number'],
                ]);
                $step->recipe()->associate($recipe);
                
                if ($request->hasFile('steps') && isset($stepData['image_url'])) {
                    // إذا تم رفع ملف صورة للخطوة الجديدة، قم بحفظها
                    $file = $request->file('steps')[$stepData['id']]['image_url'];
                    $path = $file->store('images/step');
                    
                    $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));
                    
                    $step->image_url = $storedPath;
                }
                
                $step->save();
            }
        }
    }
    
    return response()->json(['message' => 'success', 'Recipe' => $recipe], 200);
}

    public function destroy($id)
    {

        $recipe = Recipe::find($id);
        if ($recipe) {
            $recipe->allRecipeIngredients()->delete();

            foreach ($recipe->steps as $step) {
                if (isset($step->image_url)) {
                    $fullPath = $step->image_url;
                    $shortPath = str_replace("C:\\Users\\scc-asus\\Desktop\\Project2\\yam\\storage\\app/", "", $fullPath);
                    Storage::delete($shortPath);
                }
            }
            $recipe->steps()->delete();
            $fullPath = $recipe->main_img_url;
            $shortPath = str_replace("C:\\Users\\scc-asus\\Desktop\\Project2\\yam\\storage\\app/", "", $fullPath);
            Storage::delete($shortPath);
            $recipe->delete();
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'Recipe not found'], 404);
        }
    }
}
