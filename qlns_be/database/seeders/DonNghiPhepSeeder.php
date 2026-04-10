<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DonNghiPhepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $data = [
            ['id_nhan_vien' => 5, 'loai_nghi' => 'phep_nam',    'ngay_bat_dau' => '2024-11-10', 'ngay_ket_thuc' => '2024-11-12', 'ly_do' => 'Nghỉ phép năm theo kế hoạch',         'trang_thai' => 2, 'id_nguoi_duyet' => 2, 'ngay_duyet' => '2024-11-08 09:00:00'],
            ['id_nhan_vien' => 3, 'loai_nghi' => 'om',          'ngay_bat_dau' => '2024-11-05', 'ngay_ket_thuc' => '2024-11-06', 'ly_do' => 'Sốt cao, có giấy nghỉ của bác sĩ',     'trang_thai' => 2, 'id_nguoi_duyet' => 2, 'ngay_duyet' => '2024-11-05 07:30:00'],
            ['id_nhan_vien' => 6, 'loai_nghi' => 'khong_luong', 'ngay_bat_dau' => '2024-12-01', 'ngay_ket_thuc' => '2024-12-03', 'ly_do' => 'Việc gia đình đột xuất',               'trang_thai' => 1, 'id_nguoi_duyet' => null, 'ngay_duyet' => null],
            ['id_nhan_vien' => 4, 'loai_nghi' => 'phep_nam',    'ngay_bat_dau' => '2024-12-20', 'ngay_ket_thuc' => '2024-12-25', 'ly_do' => 'Nghỉ lễ Giáng sinh và cuối năm',      'trang_thai' => 1, 'id_nguoi_duyet' => null, 'ngay_duyet' => null],
            ['id_nhan_vien' => 7, 'loai_nghi' => 'om',          'ngay_bat_dau' => '2024-10-15', 'ngay_ket_thuc' => '2024-10-15', 'ly_do' => 'Khám sức khoẻ định kỳ',               'trang_thai' => 2, 'id_nguoi_duyet' => 3, 'ngay_duyet' => '2024-10-14 16:00:00'],
            ['id_nhan_vien' => 8, 'loai_nghi' => 'phep_nam',    'ngay_bat_dau' => '2024-11-25', 'ngay_ket_thuc' => '2024-11-26', 'ly_do' => 'Dự đám cưới người thân',              'trang_thai' => 3, 'id_nguoi_duyet' => 2, 'ngay_duyet' => '2024-11-20 10:00:00'],
            ['id_nhan_vien' => 2, 'loai_nghi' => 'khong_luong', 'ngay_bat_dau' => '2025-01-02', 'ngay_ket_thuc' => '2025-01-03', 'ly_do' => 'Xử lý thủ tục hành chính cá nhân',   'trang_thai' => 1, 'id_nguoi_duyet' => null, 'ngay_duyet' => null],
            ['id_nhan_vien' => 1, 'loai_nghi' => 'phep_nam',    'ngay_bat_dau' => '2025-02-10', 'ngay_ket_thuc' => '2025-02-14', 'ly_do' => 'Nghỉ Tết Nguyên Đán bổ sung',        'trang_thai' => 2, 'id_nguoi_duyet' => 1, 'ngay_duyet' => '2025-01-20 11:00:00'],
        ];

        foreach ($data as $item) {
            DB::table('don_nghi_pheps')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
