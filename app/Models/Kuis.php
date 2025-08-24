<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = "kuis";
    protected $primaryKey = "kuis_id";

    protected $fillable = [
        'nama'
    ];
}
