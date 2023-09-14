<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;
    protected  $guarded = [];

    public function UserDocument()
    {
        return $this->BelongsTo(User::class,'user_id');
    }
}
