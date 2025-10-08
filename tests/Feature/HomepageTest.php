<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/homepage');
    $response->assertRedirect('/');
});

test('authenticated users can visit the homepage', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/homepage');
    $response->assertStatus(200);
});