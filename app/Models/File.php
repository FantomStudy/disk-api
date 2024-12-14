<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'user_id',
        'file_id'
    ];
    public function user() : belongsTo{
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
