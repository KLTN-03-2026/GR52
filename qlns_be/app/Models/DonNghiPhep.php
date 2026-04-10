<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonNghiPhep extends Model
{

    protected $table = 'don_nghi_pheps';

    protected $fillable = [
        'id_nhan_vien',
        'loai_nghi',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ly_do',
        'trang_thai',
        'id_nguoi_duyet',
        'ngay_duyet',
    ];

    protected $casts = [
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
        'ngay_duyet'    => 'datetime',
        'trang_thai'    => 'integer',
    ];
}
