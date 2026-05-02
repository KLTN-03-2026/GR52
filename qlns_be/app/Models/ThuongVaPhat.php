<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThuongVaPhat extends Model
{
    protected $table    = 'thuong_va_phats';
    protected $fillable = ['id_nhan_vien', 'thang', 'nam', 'loai', 'so_tien', 'ly_do', 'id_nguoi_tao'];
    protected $casts    = ['so_tien' => 'decimal:0', 'loai' => 'string'];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'id_nhan_vien');
    }
    public function nguoiTao()
    {
        return $this->belongsTo(NhanVien::class, 'id_nguoi_tao');
    }
}
