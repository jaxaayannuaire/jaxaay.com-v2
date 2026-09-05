<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return PlanResource::collection(
            Plan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
        );
    }

    public function show(Request $request, Plan $plan)
    {
        abort_unless($plan->is_active, 404);

        return new PlanResource($plan);
    }
}
