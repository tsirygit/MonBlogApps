<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Events\CommentEvent;
use Illuminate\Support\Facades\Event;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('seul les utilisateur connecté est commenté les post', function () {

    // lance un evenement faker pour ecouter le evenement
    Event::fake();

    // Crée un utilisateur
    $user = User::factory()->create();

    // Crée un post appartenant à cet utilisateur
    $post = Post::factory()->create([
        'user_id' => $user->id,
    ]);

    // Simule une session authentifiée
    $this->actingAs($user);

    // Accède au formulaire de création de commentaire
    $response = $this->get("/comments/create/{$post->id}");
    $response->assertStatus(200);

    // Soumet un commentaire
    $response = $this->post('/comments', [
        'post_id' => $post->id,
        'content' => 'test commentaire',
    ]);

    Event::assertDispatched(CommentEvent::class);

    // Vérifie que l'utilisateur est bien connecté
    $this->assertAuthenticated();

    //recuperer le commentaire creé 

    $comment = Comment::where('content', 'test commentaire')->first();
    // Vérifie la redirection vers la page du post


    $response->assertRedirect(route('post.show', $comment->post_id));
});

it('autorise uniquement pour le proprietaire de commentaire via laravel policie ', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create();


    $comment = Comment::factory()->create([
        'user_id' => $owner->id,
        'post_id' => $post->id,
        'content' => 'Commentaire original',
    ]);

    // Le propriétaire peut modifier

    $this->actingAs($owner)->get('Comments/Edit');

    $response = $this->patch("/comments/{$comment->id}", [
        'content' => 'Commentaire modifié',
    ]);


    $response->assertRedirect(route('post.show', $comment->post_id));
    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'content' => 'Commentaire modifié',
    ]);

    // Un autre utilisateur ne peut pas modifier
    $this->actingAs($otherUser);

    $response = $this->patch("/comments/{$comment->id}", [
        'content' => 'Tentative de modification',
    ]);
    $response->assertStatus(403);
});

it('autorise uniquement le propriétaire à supprimer son commentaire via la policy', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $post = Post::factory()->create();

    $comment = Comment::factory()->create([
        'user_id' => $owner->id,
        'post_id' => $post->id,
        'content' => 'Commentaire original',
    ]);

    // Le propriétaire peut supprimer
    $this->actingAs($owner);
    $response = $this->delete("/comments/{$comment->id}");
    $response->assertRedirect(route('post.show', $comment->post_id));
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

    // Un autre utilisateur ne peut pas supprimer
    $comment = Comment::factory()->create([
        'user_id' => $owner->id,
        'post_id' => $post->id,
        'content' => 'Commentaire à protéger',
    ]);

    $this->actingAs($otherUser);
    $response = $this->delete("/comments/{$comment->id}");
    $response->assertStatus(403);
    $this->assertDatabaseHas('comments', ['id' => $comment->id]);
});
