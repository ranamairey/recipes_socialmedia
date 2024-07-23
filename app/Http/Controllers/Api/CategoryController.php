<?php

namespace App\Http\Controllers\Api;
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    public function index(Request $request): CategoryCollection
    {
        $this->authorize('view-any', Category::class);

        $search = $request->get('search', '');

        $categories = Category::search($search)
            ->latest()
            ->paginate();

        return new CategoryCollection($categories);
    }

    public function store(CategoryStoreRequest $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] =$request->image->store('images/category');
        }
        $category = Category::create($validated);
    return response()->json(['message' => 'Section added successfully', 'category' => $category], 201);
}


    public function show(Request $request, Category $category): CategoryResource
    {
        $this->authorize('view', $category);

        return new CategoryResource($category);
    }

    public function update(
        CategoryUpdateRequest $request,
        Category $category
    ): CategoryResource {
        $this->authorize('update', $category);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::delete($category->image);
            }

            $validated['image'] = $request->image->store('images/category');
        }

        $category->update($validated);

        return new CategoryResource($category);
    }

    public function destroy(Request $request, Category $category): Response
    {
        $this->authorize('delete', $category);

        if ($category->image) {
            Storage::delete($category->image);
        }

        $category->delete();

        return response()->noContent();
    }
}
