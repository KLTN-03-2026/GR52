<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiNhanVien extends Model
{
   protected $table    = 'kpi_nhan_viens';
    protected $fillable = [
        'id_nhan_vien','id_tieu_chi','thang','nam',
        'muc_tieu','ket_qua_thuc_te','phan_tram_hoan_thanh',
        'diem_kpi','xep_loai','ghi_chu',
    ];
    protected $casts = [
        'muc_tieu'              => 'decimal:2',
        'ket_qua_thuc_te'       => 'decimal:2',
        'phan_tram_hoan_thanh'  => 'decimal:2',
        'diem_kpi'              => 'decimal:2',
    ];

    public function nhanVien()  { return $this->belongsTo(NhanVien::class, 'id_nhan_vien'); }
    public function tieuChi()   { return $this->belongsTo(TieuChiKpi::class, 'id_tieu_chi'); }
}
