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
        'so_ngay', 'ly_do','nguoi_thay_the', 'trang_thai', 'id_nguoi_duyet',
        'ngay_duyet', 'ly_do_tu_choi',
    ];

    protected $casts = [
        'ngay_bat_dau'  => 'date',
        'ngay_ket_thuc' => 'date',
        'ngay_duyet'    => 'datetime',
        'trang_thai'    => 'integer',
        'so_ngay'       => 'integer',
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
