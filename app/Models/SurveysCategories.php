<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveysCategories extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function SurveysCategories()
    {
        return $this->HasMany(Survey::class,'category');
    }
}
