<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class Section extends Model
{
    use HasFactory;
    protected $table = 'sections';
    protected $fillable = [
        'section_name',
        'description',
        'Created_by'
    ];


    public function products(){
        return $this->hasMany(Products::class);
    }
}
