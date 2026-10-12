<?php

use App\Enums\UserRole;
use App\Models\User;

it('permite a un admin crear un deudor', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->actingAs($admin, 'sanctum')
        ->postJson('/api/debtors', [
            'name' => 'Cliente Test',
            'document' => '12345678A',
            'email' => 'cliente@test.com',
        ]);

    $response->assertCreated();
    $this->assertDatabaseHas('debtors', ['document' => '12345678A']);
});

it('rechaza a un gestor crear un deudor con 403', function () {
    $gestor = User::factory()->create(['role' => UserRole::Gestor]);

    $response = $this->actingAs($gestor, 'sanctum')
        ->postJson('/api/debtors', [
            'name' => 'Cliente Test',
            'document' => '12345678A',
        ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('debtors', ['document' => '12345678A']);
});
