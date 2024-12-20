<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    //
    use HasFactory;

    protected $fillable= [
        'name',
        'path',
        'size',
        'user_id',
        'file_id',
    ];

    public function user() : belongsTo{
        return $this->belongsTo(User::class, "user_id", 'id');
    }
    public function url() : Attribute{
        return Attribute::get(fn() => route('download', $this));
    }
    public function getAccessArray() {
        $accesses = [
            [
                'full_name' => $this->user->first_name . ' ' . $this->user->last_name,
                'email' => $this->user->email,
                'type' => 'author'
            ]
        ];

        $relations = FileAccess::query()->where('file_id', $this->id)->get();

        foreach ($relations as $relation) {
            $accesses [] = [
                'full_name' => $relation->user->first_name . ' ' . $relation->user->last_name,
                'email' => $relation->user->email,
                'type' => 'co-author'
            ];
        }
        return $accesses;
    }
}
