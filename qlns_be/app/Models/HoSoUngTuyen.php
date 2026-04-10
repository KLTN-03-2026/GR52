<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoSoUngTuyen extends Model
{
   protected $table = 'ho_so_ung_tuyens';

    protected $fillable = [
        'id_ung_vien',
        'id_chuc_vu',
        'ngay_ung_tuyen',
        'trang_thai',
        'trang_thai_cu',
        'ngay_cap_nhat',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_ung_tuyen' => 'datetime',
        'ngay_cap_nhat'  => 'datetime',
        'trang_thai'     => 'integer',
    ];
}
