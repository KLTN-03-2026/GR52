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
        'trang_thai',
        'chu_ky_admin',
        'ngay_admin_ky',
        'chu_ky_nhan_vien',
        'ngay_nhan_vien_ky',
        'file_pdf',
    ];

    protected $casts = [
        'ngay_ky'       => 'date',
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
        'ngay_admin_ky' => 'datetime',
        'ngay_nhan_vien_ky' => 'datetime',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }

    public function loaiHopDong()
    {
        return $this->belongsTo(LoaiHopDong::class, 'id_loai_hop_dong');
    }
}
