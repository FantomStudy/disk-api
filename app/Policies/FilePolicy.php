<?php

namespace App\Policies;

use App\Models\File;
use App\Models\FileAccess;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function manage(User $user, File $file){
        return $user->id == $file->user_id;
    }
    public function view(User $user, File $file){
        return $this->manage($user, $file) || FileAccess::query()->where('user_id', $user->id)->where('file_id', $file->id)->exists();
    }
}
