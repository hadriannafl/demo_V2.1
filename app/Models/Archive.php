<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $table = 't_archive';
    protected $primaryKey = 'idrec';
    protected $fillable = [
        'date',
        'no_archive',
        'id_aju',
        'pdf_jpg',
        'file_name',
        'active_y_n',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi dengan Aju (1 Archive hanya memiliki 1 Aju)
     */
    public function aju()
    {
        return $this->belongsTo(Aju::class, 'id_aju', 'id_aju');
    }

}
