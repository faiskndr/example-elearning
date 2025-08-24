<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSoalOpsi extends Model
{
    protected $table = "kuis_soal_opsi";
    protected $primaryKey = "kuis_soal_opsi_id";

     protected $guarded = [
        'kuis_soal_opsi_id'
    ];
}
