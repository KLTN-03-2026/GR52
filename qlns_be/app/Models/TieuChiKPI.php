<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TieuChiKpi extends Model
{
   protected $table = 'tieu_chi_kpis';

    protected $fillable = [
        'ten_tieu_chi',
        'mo_ta',
        'diem',
        'tinh_trang',
    ];
}
