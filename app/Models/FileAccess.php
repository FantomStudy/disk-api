<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FileAccess extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'file_id',
        'user_id',
    ];
    public function file() :hasOne{
        return $this->hasOne(File::class, 'id', 'file_id');
    }
    public function user() :hasOne{
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
