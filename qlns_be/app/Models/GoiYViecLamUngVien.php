<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoiYViecLamUngVien extends Model
{
    use HasFactory;

    protected $table = 'goi_y_viec_lam_ung_viens';

    protected $fillable = [
        'ung_vien_id',
        'vi_tri_tuyen_dung_id',
        'danh_gia_cv_ung_tuyen_id',
        'diem_phu_hop',
        'ly_do',
        'ky_nang_phu_hop',
        'trang_thai',
    ];

    protected $casts = [
        'ky_nang_phu_hop' => 'array',
    ];

    public function ungVien()
    {
        return $this->belongsTo(UngVien::class, 'ung_vien_id');
    }

    public function viTriTuyenDung()
    {
        return $this->belongsTo(ViTriTuyenDung::class, 'vi_tri_tuyen_dung_id');
    }

    public function danhGiaCV()
    {
        return $this->belongsTo(DanhGiaCVUngTuyen::class, 'danh_gia_cv_ung_tuyen_id');
    }
}
