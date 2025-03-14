<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'm_brand';
    protected $primaryKey = 'id_brand';
    protected $fillable = [
        'name',
        'p_id_brand',
        'status',
    ];
    public $timestamps = true;
}
