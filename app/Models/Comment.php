<?php

namespace App\Models;

use App\HasFormattedDates;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFormattedDates;
    use HasFactory;

    protected $appends = ['created_at_formatted'];
    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    /**
     * Relation entre le modèle Comment et Post.
     * Un commentaire appartient à un seul post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relation entre le modèle Comment et User.
     * Un commentaire appartient à un utilisateur.
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
