<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aju extends Model
{
    use HasFactory;

    protected $table = 't_aju';
    protected $primaryKey = 'idrec';
    protected $fillable = [
        'date',
        'no_aju',
        'id_archive',
        'active_y_n',
        'created_by',
        'updated_by'
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class, 'id_archive');
    }
}
