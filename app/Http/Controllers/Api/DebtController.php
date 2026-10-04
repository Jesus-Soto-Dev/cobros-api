<?php

namespace App\Http\Controllers\Api;

use App\Enums\DebtStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDebtRequest;
use App\Http\Requests\UpdateDebtRequest;
use App\Http\Resources\DebtResource;
use App\Models\Debt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DebtController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Debt::query()->with('debtor');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('debtor_id')) {
            $query->where('debtor_id', $request->integer('debtor_id'));
        }

        if ($request->boolean('overdue')) {
            $query->where('due_date', '<', now()->toDateString())
                ->whereNotIn('status', [
                    DebtStatus::Paid->value,
                    DebtStatus::Uncollectible->value,
                ]);
        }

        $debts = $query->orderByDesc('due_date')->paginate(20);

        return DebtResource::collection($debts);
    }

    public function store(StoreDebtRequest $request): JsonResponse
    {
        $debt = Debt::create($request->validated());
        $debt->refresh()->load('debtor');

        return (new DebtResource($debt))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Debt $debt): DebtResource
    {
        $debt->load(['debtor', 'actions.user', 'payments']);

        return new DebtResource($debt);
    }

    public function update(UpdateDebtRequest $request, Debt $debt): DebtResource
    {
        $debt->update($request->validated());
        $debt->refresh()->load('debtor');

        return new DebtResource($debt);
    }

    public function destroy(Debt $debt): JsonResponse
    {
        $debt->delete();

        return response()->json(null, 204);
    }
}
