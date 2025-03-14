<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $table = 't_archive';
    protected $primaryKey = 'id_archive';
    protected $fillable = [];

    public function ajus()
    {
        return $this->hasMany(Aju::class, 'id_archive');
    }
    
    public function department()
    {
        return $this->belongsTo(MDepartment::class, 'id_department', 'id');
    }

    public function softDelete()
    {
        $this->update(['active_y_n' => 'n']);
    }
}
