<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TieuChiKpi extends Model
{
    protected $table    = 'tieu_chi_kpis';
    protected $fillable = ['ten_tieu_chi','mo_ta','trong_so','muc_tieu','don_vi','tinh_trang'];
    protected $casts    = ['trong_so' => 'decimal:2', 'muc_tieu' => 'decimal:2', 'tinh_trang' => 'integer'];

    public function kpiNhanViens()
    {
        return $this->hasMany(KpiNhanVien::class, 'id_tieu_chi');
    }
}
