<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class NhanVien extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'nhan_viens';

    protected $fillable = [
        'id_phong_ban',
        'id_chuc_vu',
        'ho_va_ten',
        'email',
        'password',
        'ngay_sinh',
        'dia_chi',
        'so_dien_thoai',
        'luong_co_ban',
    ];
    protected $hidden = ['password'];

    protected $casts = [
        'ngay_sinh'    => 'date',
        'luong_co_ban' => 'decimal:2',
    ];

    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'id_phong_ban');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu');
    }

    public function hopDongs()
    {
        return $this->hasMany(ChiTietHopDong::class, 'id_nhan_vien');
    }

    public function phanQuyens()
    {
        return $this->hasMany(PhanQuyen::class, 'id_nhanvien');
    }
}
