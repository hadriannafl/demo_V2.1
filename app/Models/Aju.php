<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aju extends Model
{
    use HasFactory;

    protected $table = 't_aju';
    protected $primaryKey = 'id_aju';
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
        'updated_by',
    ];

    /**
     * Relasi dengan Archive (1 Aju bisa memiliki banyak Archive)
     */
    public function archives()
    {
        return $this->hasMany(Archive::class, 'id_aju', 'id_aju');
    }

    /**
     * Relasi dengan Department
     */
    public function department()
    {
        return $this->belongsTo(MDepartment::class, 'id_department', 'id');
    }
}
