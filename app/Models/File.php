<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'file';

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'tramite_id', 'id');
    }
}