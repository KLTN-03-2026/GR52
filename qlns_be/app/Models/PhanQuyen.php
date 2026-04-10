<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanQuyen extends Model
{
    protected $table = 'phan_quyens';

    protected $fillable = [
        'id_nhanvien',
        'ten_chuc_nang',
    ];

}
