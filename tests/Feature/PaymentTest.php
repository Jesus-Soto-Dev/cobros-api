<?php

use App\Enums\DebtStatus;
use App\Enums\UserRole;
use App\Models\Debt;
use App\Models\Debtor;
use App\Models\User;

it('registra un pago parcial y actualiza paid_amount', function () {
    $gestor = User::factory()->create(['role' => UserRole::Gestor]);
    $debtor = Debtor::factory()->create();
    $debt = Debt::factory()->create([
        'debtor_id' => $debtor->id,
        'amount' => 1000.00,
        'status' => DebtStatus::Pending,
    ]);

    $response = $this->actingAs($gestor, 'sanctum')
        ->postJson("/api/debts/{$debt->id}/payments", [
            'amount' => 300.00,
            'method' => 'transferencia',
        ]);

    $response->assertCreated();
    $this->assertDatabaseHas('payments', [
        'debt_id' => $debt->id,
        'amount' => 300.00,
    ]);

    $debt->refresh();
    expect($debt->status)->toBe(DebtStatus::Pending);
});

it('marca la deuda como pagada cuando se cubre el total', function () {
    $gestor = User::factory()->create(['role' => UserRole::Gestor]);
    $debtor = Debtor::factory()->create();
    $debt = Debt::factory()->create([
        'debtor_id' => $debtor->id,
        'amount' => 500.00,
        'status' => DebtStatus::Pending,
    ]);

    $this->actingAs($gestor, 'sanctum')
        ->postJson("/api/debts/{$debt->id}/payments", [
            'amount' => 500.00,
        ])
        ->assertCreated();

    $debt->refresh();
    expect($debt->status)->toBe(DebtStatus::Paid);
});

it('rechaza un pago en una deuda ya pagada', function () {
    $gestor = User::factory()->create(['role' => UserRole::Gestor]);
    $debtor = Debtor::factory()->create();
    $debt = Debt::factory()->create([
        'debtor_id' => $debtor->id,
        'amount' => 500.00,
        'status' => DebtStatus::Paid,
    ]);

    $this->actingAs($gestor, 'sanctum')
        ->postJson("/api/debts/{$debt->id}/payments", [
            'amount' => 100.00,
        ])
        ->assertStatus(422);
});
