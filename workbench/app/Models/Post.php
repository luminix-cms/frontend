<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Luminix\Backend\Model\LuminixModel;

class Post extends Model
{
    use HasFactory, LuminixModel;

    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
