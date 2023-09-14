<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalTestParametr extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function parent(){
        return $this->belongsto(DoctorList::class,'test_id');
    }
}
