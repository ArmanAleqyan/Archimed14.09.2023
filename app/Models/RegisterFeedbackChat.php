<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterFeedbackChat extends Model
{
    use HasFactory;
protected $guarded =[];


    public function RegisterFeedbackChat()
    {
        return $this->BelongsTo(Feedback::class,'feedback_id');
    }
}
