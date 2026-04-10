<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGiaUngVien extends Model
{
    protected $table = 'danh_gia_ung_viens';

    protected $fillable = [
        'id_ho_so_ung_tuyen',
        'id_tieu_chi',
        'diem_danh_gia',
        'id_nhan_vien_danh_gia',
    ];
}
