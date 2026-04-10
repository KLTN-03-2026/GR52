<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class UngVien extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'ung_viens';

    protected $fillable = [
        'ho_ten',
        'email',
        'password',
        'so_dien_thoai',
        'file_cv',
        'tinh_trang',
        'ghi_chu',
    ];


}
