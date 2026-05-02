<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSoUngTuyen extends Model
{
    use HasFactory;

    protected $table = 'ho_so_ung_tuyens';

    protected $fillable = [
        'ung_vien_id',
        'vi_tri_tuyen_dung_id',
        'file_cv',
        'ghi_chu_ung_vien',
        'ngay_ung_tuyen',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_ung_tuyen' => 'datetime',
    ];

    public function ungVien()
    {
        return $this->belongsTo(UngVien::class, 'ung_vien_id');
    }

    public function viTriTuyenDung()
    {
        return $this->belongsTo(ViTriTuyenDung::class, 'vi_tri_tuyen_dung_id');
    }

    public function danhGiaCVs()
    {
        return $this->hasMany(DanhGiaCVUngTuyen::class, 'ho_so_ung_tuyen_id');
    }

    public function latestDanhGiaCV()
    {
        return $this->hasOne(DanhGiaCVUngTuyen::class, 'ho_so_ung_tuyen_id')->latestOfMany();
    }
}
