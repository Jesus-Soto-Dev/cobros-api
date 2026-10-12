<?php

use App\Models\User;

it('devuelve un token cuando las credenciales son correctas', function () {
    User::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'admin@test.com',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']]);
});

it('rechaza el login con credenciales incorrectas', function () {
    User::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'admin@test.com',
        'password' => 'wrong',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
