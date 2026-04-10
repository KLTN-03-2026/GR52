<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TieuChiDanhGiaTd extends Model
{
    protected $table = 'tieu_chi_danh_gia_tds';

    protected $fillable = [
        'tieu_de',
        'mo_ta',
        'diem_toi_da',
    ];
}
