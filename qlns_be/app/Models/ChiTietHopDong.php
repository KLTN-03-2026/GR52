<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietHopDong extends Model
{
    protected $table = 'chi_tiet_hop_dongs';

    protected $fillable = [
        'id_nhan_vien',
        'id_loai_hop_dong',
        'noi_dung',
        'ngay_ky',
        'ngay_bat_dau',
        'ngay_ket_thuc',
    ];

    protected $casts = [
        'ngay_ky'       => 'date',
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
    ];
}
