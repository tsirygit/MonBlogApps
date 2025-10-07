<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Like;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation entre le modèle User et Like.
     * Un User peut avoir plusieurs likes.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relation entre le modèle User et Comment.
     * Un User peut avoir plusieurs Comment.
     */

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Vérifie si l'utilisateur a liké un post donné.
     *
     * @param int $postId
     * @return bool
     */
    public function hasLiked($postId)
    {
        return $this->likes()
            ->where('post_id', $postId)
            ->where('like', true)
            ->exists();
    }



    /**
     * Vérifie si l'utilisateur a disliké un post donné.
     *
     * @param int $postId
     * @return bool
     */
    public function hasDisliked(int $postId): bool
    {
        return $this->likes()
            ->where('post_id', $postId)
            ->where('like', false)
            ->exists();
    }

    /**
     * Gère l'action de like ou dislike sur un post.
     * Si l'utilisateur a déjà liké/disliké, met à jour ou supprime selon le cas.
     * Sinon, crée un nouveau like/dislike.
     *
     * @param int $postId
     * @param bool $like
     * @return array<string, bool>
     */


    public function LikeDislike($postId, $like)
    {

        $existingLike = $this->likes()->where('post_id', $postId)->first();

        if ($existingLike) {
            if ($existingLike->like == $like) {
                $existingLike->delete();

                return [
                    'hasLiked' => false,
                    'hasDisliked' => false
                ];
            } else {
                $existingLike->update(['like' => $like]);
            }
        } else {
            $this->likes()->create([
                'post_id' => $postId,
                'like' => $like,
            ]);
        }

        return [
            'hasLiked' => $this->hasLiked($postId),
            'hasDisliked' => $this->hasDisliked($postId)
        ];
    }
}
