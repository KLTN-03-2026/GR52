<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhGiaCVUngTuyen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'danh_gia_cv_ung_tuyens';

    protected $fillable = [
        'ho_so_ung_tuyen_id',
        'vi_tri_tuyen_dung_id',
        'created_by',
        'tong_diem',
        'khuyến_nghị',
        'kỹ_năng_phù_hợp',
        'kỹ_năng_thiếu',
        'điểm_mạnh',
        'điểm_yếu',
        'tóm_tắt_phân_tích',
        'ai_response',
        'trạng_thái',
        'lỗi',
    ];

    protected $casts = [
        'kỹ_năng_phù_hợp' => 'array',
        'kỹ_năng_thiếu' => 'array',
        'điểm_mạnh' => 'array',
        'điểm_yếu' => 'array',
    ];

    // Relationships
    public function hoSoUngTuyen()
    {
        return $this->belongsTo(HoSoUngTuyen::class, 'ho_so_ung_tuyen_id');
    }

    public function viTriTuyenDung()
    {
        return $this->belongsTo(ViTriTuyenDung::class, 'vi_tri_tuyen_dung_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(NhanVien::class, 'created_by');
    }

    // Scopes for filtering
    public function scopeByViTri($query, $viTriId)
    {
        return $query->where('vi_tri_tuyen_dung_id', $viTriId);
    }

    public function scopeByScore($query, $minScore)
    {
        return $query->where('tong_diem', '>=', $minScore);
    }

    public function scopeRecommended($query)
    {
        return $query->whereIn('khuyến_nghị', ['Phù hợp', 'Ứng viên tốt']);
    }

    // Helper methods
    public function getStatusBadge()
    {
        return match ($this->khuyến_nghị) {
            'Phù hợp' => 'success',
            'Cần xem xét' => 'warning',
            'Không phù hợp' => 'danger',
            default => 'secondary',
        };
    }

    public function getScorePercentage()
    {
        return $this->tong_diem . '%';
    }
}
