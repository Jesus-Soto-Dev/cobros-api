<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Debt;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function store(StorePaymentRequest $request, Debt $debt): JsonResponse
    {
        $payment = $this->paymentService->registerPayment($debt, $request->validated());

        $payment->load('debt');

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }
}
