<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('les utilisateurs non authentifié est pas accés aux post', function () {
    $response = $this->get('/post');

    $response->assertStatus(302);
});


it('seul les utilisateurs authentifié est peuvent voir accéder aux creation de post', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/post/create');

    $response->assertStatus(200);
});


it('seul les utilisateurs authentifié est peuvent creér de nouveul  post', function () {

    Storage::fake('public');

    $user = User::factory()->create();

    $pathImage = UploadedFile::fake()->image('test.jpg');

    $response = $this->actingAs($user)->post('/post', [

        'image' => $pathImage,
        'title' => 'titre de test',
        'content' => 'contenu de test',
        'user_id' => $user,
    ]);

    $this->assertAuthenticated();

    $response->assertRedirect(route('homepage'));
    $response->assertSessionHasNoErrors();
});

it('seuls les utilisateurs authentifié peuvent voir le post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user)
        ->get(route('post.show', $post))
        ->assertStatus(200);
});

it('seuls les propriétaires peuvent accéder à l’édition du post', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->get(route('post.edit', $post))
        ->assertStatus(200);

    $this->actingAs($otherUser)
        ->get(route('post.edit', $post))
        ->assertStatus(403);
});

it('seuls les utilisateurs authentifiés peuvent mettre à jour leur post', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $newImage = UploadedFile::fake()->image('updated.jpg');

    $response = $this->actingAs($user)->put(route('post.update', $post), [
        'image' => $newImage,
        'title' => 'titre mis à jour',
        'content' => 'contenu mis à jour',
    ]);

    $response->assertRedirect(route('homepage'));
    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'titre mis à jour',
        'content' => 'contenu mis à jour',
        'user_id' => $user->id,
    ]);

    Storage::disk('public')->assertExists('posts/' . $newImage->hashName());
});


it('seuls les propriétaires peuvent supprimer le post', function () {

    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->delete(route('post.destroy', $post))
        ->assertRedirect('homepage');
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);

    $post = Post::factory()->create(['user_id' => $owner->id]);
    $this->actingAs($otherUser)
        ->delete(route('post.destroy', $post))
        ->assertStatus(403);
    $this->assertDatabaseHas('posts', ['id' => $post->id]);
});
