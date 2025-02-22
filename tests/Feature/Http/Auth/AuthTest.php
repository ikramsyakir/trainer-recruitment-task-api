<?php

use App\Models\User;

test('user can login with valid credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    expect($response->status())->toBe(200)
        ->and($response->json())->toMatchArray([
            'status' => true,
            'message' => 'User successfully logged in',
        ])
        ->and($response->json())->toHaveKey('token');
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    expect($response->status())->toBe(401)
        ->and($response->json())->toMatchArray([
            'status' => false,
            'message' => 'Login credentials do not match our records',
        ]);
});

test('user can register successfully', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson(route('register'), $userData);

    expect($response->status())->toBe(201)
        ->and($response->json())->toMatchArray([
            'status' => true,
            'message' => 'User successfully registered',
        ])
        ->and(User::where('email', 'johndoe@example.com')->exists())->toBeTrue();
});

test('user can logout successfully', function () {
    // Create a user
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    // Authenticate and get a token
    $token = $user->createToken('Test Token')->plainTextToken;

    // Ensure token exists
    expect($token)->not->toBeNull();

    // Make logout request with Bearer token
    $response = $this->withHeader('Authorization', "Bearer $token")
        ->getJson(route('logout'));

    // Assertions
    expect($response->status())->toBe(200)
        ->and($response->json())->toMatchArray([
            'status' => true,
            'message' => 'User successfully logged out',
        ])
        ->and($user->tokens()->count())->toBe(0);
});
