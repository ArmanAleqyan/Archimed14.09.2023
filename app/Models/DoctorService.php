<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorService extends Model
{
    use HasFactory;
    protected $guarded =[];

//    public function Basket(): MorphMany{
//        return $this->morphMany(Basket::class,'parent_id');
//    }

    public function get_order(){
        return $this->belongsTo(Basket::class,'App\Models\DoctorService','id','parent_id');
    }

    public function get_services_review(){
        return $this->hasMany(Review::class,'doctor_services_id');
    }

}
