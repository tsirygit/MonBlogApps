<?php

use App\Models\Post;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it(' seul un utilisateur authentifié peut liker, disliker et retirer son vote', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    
    // Like
    $result = $user->LikeDislike($post->id, true);
    expect($result)->toMatchArray(['hasLiked' => true, 'hasDisliked' => false]);
    $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'post_id' => $post->id, 'like' => true]);

    // Dislike
    $result = $user->LikeDislike($post->id, false);
    expect($result)->toMatchArray(['hasLiked' => false, 'hasDisliked' => true]);
    $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'post_id' => $post->id, 'like' => false]);

    // Retirer le dislike
    $result = $user->LikeDislike($post->id, false);
    expect($result)->toMatchArray(['hasLiked' => false, 'hasDisliked' => false]);
    $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'post_id' => $post->id]);
});
