<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSoal extends Model
{
    protected $table = "kuis_soal";
    protected $primaryKey = "kuis_soal_id";

    protected $guarded = [
        'kuis_soal_id'
    ];

    public function soalOpsiRelations()
    {
        return $this->hasMany(KuisSoalOpsi::class, 'kuis_soal_id', 'kuis_soal_id');
    }
}
