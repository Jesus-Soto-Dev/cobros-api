<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDebtActionRequest;
use App\Http\Resources\DebtActionResource;
use App\Models\Debt;
use Illuminate\Http\JsonResponse;

class DebtActionController extends Controller
{
    public function store(StoreDebtActionRequest $request, Debt $debt): JsonResponse
    {
        $action = $debt->actions()->create([
            'user_id' => $request->user()->id,
            'type' => $request->validated('type'),
            'notes' => $request->validated('notes'),
            'action_date' => $request->validated('action_date') ?? now(),
        ]);

        $action->load('user');

        return (new DebtActionResource($action))
            ->response()
            ->setStatusCode(201);
    }
}
