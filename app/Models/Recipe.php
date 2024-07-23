<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Models\Ingredients;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'description',
        'tips',
        'main_img_url',
        'views',
        'expected_cost',
        'expected_time',
        'difficulty_level',
    ];

    protected $searchableFields = ['*'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function steps()
    {
        return $this->hasMany(Step::class);
    }
    public function allRecipeIngredients()
    {
        return $this->hasMany(RecipeIngredients::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    public function favRecipes()
    {
        return $this->hasMany(FavRecipe::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
