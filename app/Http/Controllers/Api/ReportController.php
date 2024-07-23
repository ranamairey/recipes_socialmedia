<?php

namespace App\Http\Controllers\Api;

use App\Models\Recipe;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Http\Resources\ReportCollection;
use App\Http\Requests\ReportStoreRequest;
use App\Http\Requests\ReportUpdateRequest;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    public function index(Request $request,Recipe $recipe)
    {
        $report = $recipe->reports()->get();

        return response()->json($report, status: Response::HTTP_ACCEPTED);
    }

    public function store(ReportStoreRequest $request)
    {
        $rate = Report::query()->create([
            'user_id' => auth()->user()->id,
            'recipe_id' => $request->recipe,
            'text' => $request->text,
        ]);
        return response()->json($rate->fresh(), status: Response::HTTP_CREATED);
    }


    public function show(Request $request, Report $report, Recipe $recipe)
    {
        $report = $recipe->reports()->find($request->id);

        return response()->json($report, status: Response::HTTP_ACCEPTED);
    }

    public function update(ReportUpdateRequest $request, Report $report, Recipe $recipe)
    {

        $report = $recipe->reports()->find($request->id);

        if (Auth::id() == $report->user_id) {
            $report->update([
                'text' => $request->text,
            ]);
            return response()->json($report, status: Response::HTTP_ACCEPTED);
        }
        echo ('you not can update rate');
        return response()->json(status: Response::HTTP_FORBIDDEN);
    }

    public function destroy(Request $request, Report $report)
    {
    }
}
