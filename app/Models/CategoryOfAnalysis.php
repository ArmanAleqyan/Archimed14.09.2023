<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOfAnalysis extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function Category()
    {
        return $this->HasMany(analysis::class,'category');
    }

    public function ComplexCategory()
    {
        return $this->hasMany(ComplexOfAnalizes::class,'category');
    }
}
