<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

/** @var \Tests\TestCase $this */

test('user can request password reset link', function () {
    Mail::fake();
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    // Llamar al método de envío de link de reset directamente
    $response = Password::sendResetLink(['email' => 'test@example.com']);

    expect($response)->toBe(Password::RESET_LINK_SENT);

    // Verificar que la notificación fue enviada
    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('user cannot request password reset with invalid email', function () {
    $response = $this->postJson('/api/password/forgot', [
        'email' => 'nonexistent@example.com',
    ]);

    $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'We can\'t find a user with that email address.',
            ]);
});

test('forgot password validates email field', function () {
    $response = $this->postJson('/api/password/forgot', []);

    $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
            ])
            ->assertJsonValidationErrors('email');
});

test('user can reset password with valid token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('old-password'),
    ]);

    $token = Password::createToken($user);

    $response = $this->postJson('/api/password/reset', [
        'token' => $token,
        'email' => 'test@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Password has been reset successfully',
            ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('user cannot reset password with invalid token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->postJson('/api/password/reset', [
        'token' => 'invalid-token',
        'email' => 'test@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unable to reset password',
            ]);
});

test('password reset validates required fields', function () {
    $response = $this->postJson('/api/password/reset', []);

    $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Validation errors',
            ])
            ->assertJsonValidationErrors(['token', 'email', 'password']);
});

test('password reset validates password confirmation', function () {
    $response = $this->postJson('/api/password/reset', [
        'token' => 'some-token',
        'email' => 'test@example.com',
        'password' => 'new-password',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Validation errors',
            ])
            ->assertJsonValidationErrors('password');
});