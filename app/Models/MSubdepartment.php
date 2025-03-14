<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSubdepartment extends Model
{
    use HasFactory;

    protected $table = 'm_subdepartment';

    protected $fillable = [
        'name',
        'p_id_dept',
        'dept_name',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(MDepartment::class, 'p_id_dept', 'id');
    }
}
