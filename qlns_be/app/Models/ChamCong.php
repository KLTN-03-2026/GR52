<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ChamCong extends Model
{

    use HasFactory;
    protected $table = 'cham_congs';

    protected $fillable = [
        'id_nhan_vien', 'ngay_lam_viec', 'ca_lam',
        'gio_vao', 'gio_ra', 'so_gio_lam',
        'anh_check_in', 'anh_check_out',
        'trang_thai', 'ghi_chu_nhan_vien', 'ghi_chu_admin',
    ];

    protected $casts = [
        'ngay_lam_viec' => 'date',
        'so_gio_lam'    => 'decimal:2',
        'trang_thai'    => 'integer',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }
}
