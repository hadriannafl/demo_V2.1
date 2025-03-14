<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $table = 't_archive';
    protected $primaryKey = 'id_archive';
    protected $fillable = [
        'date',
        'id_department',
        'tipe_docs',
        'no_docs',
        'description',
        'pdf_jpg',
        'file_name',
        'active_y_n',
        'created_by',
        'updated_by'
    ];

    public function ajus()
    {
        return $this->hasMany(Aju::class, 'id_archive');
    }
    
    public function department()
    {
        return $this->belongsTo(MDepartment::class, 'id_department', 'id');
    }
}
