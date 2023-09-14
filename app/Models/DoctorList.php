<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DoctorList extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function DoctorsSubject()
    {
        return $this->HasMany(DoctorSubject::class,'doctor_id');
    }

    public function DoctorService()
    {
        return $this->HasMany(DoctorService::class,'doctor_id');
    }



    public function get_orders(){
        return $this->HasMany(Basket::class,'user_id');
    }

}
