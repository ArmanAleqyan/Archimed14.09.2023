<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function SurveysCategories()
    {
        return $this->Belongsto(SurveysCategories::class,'category');
    }

    public function SurveysComplex()
    {
        return $this->Belongsto(ComplexOfAnalizes::class,'complex');
    }
}
