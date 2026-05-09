<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Luminix\Backend\Model\LuminixModel;
use Workbench\Database\Factories\PostFactory;

class Post extends Model
{
    use LuminixModel, SoftDeletes, HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}
