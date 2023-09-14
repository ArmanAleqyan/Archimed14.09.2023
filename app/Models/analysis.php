<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analysis extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function CategoryOfAnalysis()
    {
        return $this->BelongsTo(CategoryOfAnalysis::class,'category');
    }

    public function ComplexOfAnalyzes()
    {
        return $this->BelongsTo(ComplexOfAnalizes::class,'complex');
    }
}
