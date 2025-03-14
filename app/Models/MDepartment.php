<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDepartment extends Model
{
    use HasFactory;

    protected $table = 'm_department';

    protected $fillable = [
        'name',
        'status',
    ];

    public function subdepartments()
    {
        return $this->hasMany(MSubdepartment::class, 'p_id_dept', 'id');
    }
}
