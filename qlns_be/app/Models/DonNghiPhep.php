<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DonNghiPhep extends Model
{

     use HasFactory;

    protected $table = 'don_nghi_pheps';

    protected $fillable = [
        'id_nhan_vien', 'loai_nghi', 'ngay_bat_dau', 'ngay_ket_thuc',
        'so_ngay', 'so_ngay_co_luong', 'so_ngay_khong_luong', 'ly_do',
        'nguoi_thay_the', 'trang_thai', 'id_nguoi_duyet', 'ngay_duyet',
        'ly_do_tu_choi', 'ghi_chu_duyet',
    ];

    protected $casts = [
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
        'ngay_duyet'    => 'datetime',
        'trang_thai'    => 'integer',
        'so_ngay'       => 'integer',
        'so_ngay_co_luong' => 'integer',
        'so_ngay_khong_luong' => 'integer',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }

    public function nguoiDuyet()
    {
        return $this->belongsTo(NhanVien::class, 'id_nguoi_duyet');
    }
}
