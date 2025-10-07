<?php

namespace App\Models;

use App\HasFormattedDates;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, HasFormattedDates;

    protected $appends = ['created_at_formatted'];

    protected $fillable = [
        'image',
        'title',
        'content',
        'user_id',
    ];

    /**
     * Relation entre le modèle Post et Like.
     * Un post peut avoir plusieurs like.
     */

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class)->where('like', true);
    }

    /**
     * Relation entre le modèle Post et Like.
     * Un post peut avoir plusieurs dislike.
     */

    public function dislikes(): HasMany
    {
        return $this->hasMany(Like::class)->where('like', false);
    }

    /**
     * Relation entre le modèle Post et Comment.
     * Un post peut avoir plusieurs commentaires.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation entre le modèle Post et User.
     * Un post appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
