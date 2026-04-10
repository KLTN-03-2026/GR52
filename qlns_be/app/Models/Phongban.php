<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhongBan extends Model
{
    protected $table = 'phong_bans';

    protected $fillable = [
        'ten_phong_ban',
        'id_truong_phong',
        'id_phong_ban_cha',
        'tinh_trang',
    ];

    public function phongBanCha()
    {
        return $this->belongsTo(PhongBan::class, 'id_phong_ban_cha');
    }

    public function phongBanCon()
    {
        return $this->hasMany(PhongBan::class, 'id_phong_ban_cha');
    }

    public function truongPhong()
    {
        return $this->belongsTo(NhanVien::class, 'id_truong_phong');
    }

    public function nhanViens()
    {
        return $this->hasMany(NhanVien::class, 'id_phong_ban');
    }
}
