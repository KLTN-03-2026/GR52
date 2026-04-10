<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChamCong extends Model
{
     protected $table = 'cham_congs';

    protected $fillable = [
        'id_nhan_vien',
        'ngay_lam_viec',
        'ca_lam',
    ];

    protected $casts = [
        'ngay_lam_viec' => 'datetime',
    ];
}
