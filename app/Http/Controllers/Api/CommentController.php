<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentCollection;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Empty_;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class CommentController extends Controller
{
    public function index(Recipe $recipe, Request $request)
    {
        $comment = $recipe->comments()->get();
        return response()->json($comment, status: Response::HTTP_OK);
    }

    public function store(CommentStoreRequest $request)
    {
        //$this->authorize('create', Comment::class);
        $comment = Comment::query()->create([
            'user_id' => auth()->user()->id,
            'recipe_id' => $request->recipe,
            'comment' => $request->comment,
        ]);
        return response()->json($comment->fresh(), status: Response::HTTP_CREATED);


        //return new CommentResource($comment);
    }

    public function show(Request $request, Recipe $recipe)
    {
        $comment = $recipe->comments()->find($request->id);

        return response()->json($comment, status: Response::HTTP_CREATED);
    }

    public function update(CommentUpdateRequest $request, Recipe $recipe, Comment $comment)
    {

        $comment = $recipe->comments()->find($request->id);

        if (Auth::id() == $comment->user_id) {
            $comment->update([
                'comment' => $request->comment,
            ]);
            return response()->json($comment, status: Response::HTTP_ACCEPTED);
        }
        echo ('you not can update');
        return response()->json(status: Response::HTTP_FORBIDDEN);
    }

    public function destroy(Comment $comment, Request $request, Recipe $recipe)
    {
        $comment = $recipe->comments()->find($request->id);

        if (Auth::id() == $comment->user_id) {
            $comment->delete();
            return response()->json($comment, status: Response::HTTP_ACCEPTED);
        }
        echo ('you not can delete');
        return response()->json(status: Response:: HTTP_FORBIDDEN);
    }
}
