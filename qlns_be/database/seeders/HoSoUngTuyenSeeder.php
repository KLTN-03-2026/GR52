<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HoSoUngTuyenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   

public function run(): void
{
    $data = [
        [
            'ung_vien_id' => 1,
            'vi_tri_tuyen_dung_id' => 5,
            'ngay_ung_tuyen' => '2024-10-05',
            'trang_thai' => 3,
            'ghi_chu_ung_vien' => 'Đã phỏng vấn vòng 2',
            'file_cv' => 'cv_1.pdf',
        ],
        [
            'ung_vien_id' => 2,
            'vi_tri_tuyen_dung_id' => 5,
            'ngay_ung_tuyen' => '2024-10-06',
            'trang_thai' => 2,
            'ghi_chu_ung_vien' => 'Đang chờ phỏng vấn vòng 1',
            'file_cv' => 'cv_2.pdf',
        ],
        [
            'ung_vien_id' => 3,
            'vi_tri_tuyen_dung_id' => 5,
            'ngay_ung_tuyen' => '2024-11-02',
            'trang_thai' => 1,
            'ghi_chu_ung_vien' => 'Mới nộp, chờ xét hồ sơ',
            'file_cv' => 'cv_3.pdf',
        ],
        [
            'ung_vien_id' => 4,
            'vi_tri_tuyen_dung_id' => 5,
            'ngay_ung_tuyen' => '2024-11-03',
            'trang_thai' => 2,
            'ghi_chu_ung_vien' => null,
            'file_cv' => 'cv_4.pdf',
        ],
        [
            'ung_vien_id' => 5,
            'vi_tri_tuyen_dung_id' => 5,
            'ngay_ung_tuyen' => '2024-12-01',
            'trang_thai' => 1,
            'ghi_chu_ung_vien' => 'Hồ sơ đẹp',
            'file_cv' => 'cv_5.pdf',
        ],
        [
            'ung_vien_id' => 6,
            'vi_tri_tuyen_dung_id' => 5,
            'ngay_ung_tuyen' => '2024-12-02',
            'trang_thai' => 4,
            'ghi_chu_ung_vien' => 'Trúng tuyển — đang ký hợp đồng',
            'file_cv' => 'cv_6.pdf',
        ],
        [
            'ung_vien_id' => 7,
            'vi_tri_tuyen_dung_id' => 7,
            'ngay_ung_tuyen' => '2025-01-07',
            'trang_thai' => 1,
            'ghi_chu_ung_vien' => 'Thực tập sinh tiềm năng',
            'file_cv' => 'cv_7.pdf',
        ],
        [
            'ung_vien_id' => 8,
            'vi_tri_tuyen_dung_id' => 7,
            'ngay_ung_tuyen' => '2025-01-08',
            'trang_thai' => 2,
            'ghi_chu_ung_vien' => null,
            'file_cv' => 'cv_8.pdf',
        ],
    ];

    foreach ($data as $item) {
        DB::table('ho_so_ung_tuyens')->insertOrIgnore(array_merge($item, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }
}
}
