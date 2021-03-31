<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Order by id DESC
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderByDesc('id');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
