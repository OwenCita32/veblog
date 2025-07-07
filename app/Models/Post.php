<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author_id', 'category_id', 'slug', 'body'];

    protected $with = ['author', 'category'];

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category() :BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    #[Scope]
    public function scopeFilter(Builder $query, array $filters): void
    {
        // $query->when($filters['search'] ?? false, function ($query, $search) {
        //     return $query->where('title', 'like', '%' . $search . '%')
        //         ->orWhere('body', 'like', '%' . $search . '%');
        // });

        // Bungkus semua kondisi search dalam satu grup 'AND'
        $query->when($filters['search'] ?? false, function (Builder $query, $search) {
          $query->where(function (Builder $query) use ($search) { // <-- TAMBAHKAN INI
              $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%');
          });
        });

        $query->when($filters['category'] ?? false, function ($query, $category) {
            return $query->whereHas('category', fn(Builder $query) =>
                $query->where('slug', $category)
            );
        });

        $query->when($filters['author'] ?? false, function ($query, $author) {
            return $query->whereHas('author', fn (Builder $query) =>
                $query->where('username', $author)
            );
        });

        // --- DEBUG POINT 2: Periksa query SQL dan binding setelah semua filter ---

    }
}
