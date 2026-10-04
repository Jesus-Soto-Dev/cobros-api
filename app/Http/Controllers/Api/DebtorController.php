<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDebtorRequest;
use App\Http\Requests\UpdateDebtorRequest;
use App\Http\Resources\DebtorResource;
use App\Models\Debtor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DebtorController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $debtors = Debtor::query()
            ->orderBy('name')
            ->paginate(20);

        return DebtorResource::collection($debtors);
    }

    public function store(StoreDebtorRequest $request): JsonResponse
    {
        $debtor = Debtor::create($request->validated());

        return (new DebtorResource($debtor))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Debtor $debtor): DebtorResource
    {
        return new DebtorResource($debtor);
    }

    public function update(UpdateDebtorRequest $request, Debtor $debtor): DebtorResource
    {
        $debtor->update($request->validated());

        return new DebtorResource($debtor);
    }

    public function destroy(Debtor $debtor): JsonResponse
    {
        $debtor->delete();

        return response()->json(null, 204);
    }
}
