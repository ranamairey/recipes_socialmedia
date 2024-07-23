<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    use Searchable;
    public $table = 'comments';
    protected $fillable = ['user_id', 'recipe_id', 'comment'];

    protected $searchableFields = ['*'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
