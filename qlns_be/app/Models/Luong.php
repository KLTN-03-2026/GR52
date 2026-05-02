<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luong extends Model
{

    protected $table    = 'luongs';
    protected $fillable = [
        'id_nhan_vien',
        'thang',
        'nam',
        'luong_co_ban',
        'so_ngay_lam_viec_chuan',
        'so_gio_lam_thuc_te',
        'gio_chuan_ngay',
        'so_ngay_nghi_co_phep',
        'so_ngay_nghi_khong_luong',
        'tong_diem_kpi',
        'xep_loai_kpi',
        'he_so_kpi',
        'tong_thuong',
        'tong_phat',
        'luong_ngay',
        'luong_thuc_te',
        'thuong_kpi',
        'khau_tru_nghi',
        'luong_truoc_thue',
        'thue_tncn',
        'luong_thuc_nhan',
        'trang_thai',
        'ghi_chu',
    ];
    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }
}
