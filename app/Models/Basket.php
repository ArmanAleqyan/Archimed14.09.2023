<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Basket extends Model
{
    use HasFactory;
    protected  $guarded = [];


    public function parent()
    {
        return $this->morphTo();
    }
    public function basketable()
    {
        return $this->morphTo();
    }

    public function get_analis_by_medical_test_parametr()
    {
        return $this->hasMany(MedicalTestParametr::class,'id','parent_id');
    }

    public function get_home_service(){
        return $this->hasMany(HomeService::class,'id','parent_id');
    }

    public function get_home_doctor_service(){
        return $this->hasMany(DoctorService::class,'id','parent_id');
    }

    public function get_medical_test_parametrs(){
        return $this->hasMany(MedicalTestParametr::class,'id','parent_id');
    }

    public function get_services(){
        return $this->morphToMany();
    }


}
