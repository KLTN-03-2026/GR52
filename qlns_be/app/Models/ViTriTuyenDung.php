<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViTriTuyenDung extends Model
{
    protected $table = 'vi_tri_tuyen_dungs';

    protected $fillable = [
        'id_phong_ban',
        'id_chuc_vu',
        'tieu_de',
        'mo_ta',
        'so_luong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'tinh_trang',
    ];

    protected $casts = [
        'ngay_bat_dau'  => 'datetime',
        'ngay_ket_thuc' => 'datetime',
    ];

    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'id_phong_ban');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu');
    }
}
