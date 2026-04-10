<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class NhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_phong_ban' => 1, 'id_chuc_vu' => 1, 'ho_va_ten' => 'Nguyễn Văn An',    'email' => 'an.nguyen@recuai.vn',    'ngay_sinh' => '1980-05-10', 'so_dien_thoai' => '0901111001', 'luong_co_ban' => 50000000],
            ['id_phong_ban' => 2, 'id_chuc_vu' => 3, 'ho_va_ten' => 'Trần Thị Bình',    'email' => 'binh.tran@recuai.vn',   'ngay_sinh' => '1985-08-22', 'so_dien_thoai' => '0901111002', 'luong_co_ban' => 25000000],
            ['id_phong_ban' => 3, 'id_chuc_vu' => 3, 'ho_va_ten' => 'Lê Hoàng Cường',   'email' => 'cuong.le@recuai.vn',    'ngay_sinh' => '1990-03-15', 'so_dien_thoai' => '0901111003', 'luong_co_ban' => 28000000],
            ['id_phong_ban' => 4, 'id_chuc_vu' => 3, 'ho_va_ten' => 'Phạm Thị Dung',    'email' => 'dung.pham@recuai.vn',   'ngay_sinh' => '1988-11-30', 'so_dien_thoai' => '0901111004', 'luong_co_ban' => 22000000],
            ['id_phong_ban' => 2, 'id_chuc_vu' => 5, 'ho_va_ten' => 'Võ Minh Đức',      'email' => 'duc.vo@recuai.vn',      'ngay_sinh' => '1995-07-04', 'so_dien_thoai' => '0901111005', 'luong_co_ban' => 15000000],
            ['id_phong_ban' => 3, 'id_chuc_vu' => 5, 'ho_va_ten' => 'Đặng Thị Hoa',     'email' => 'hoa.dang@recuai.vn',    'ngay_sinh' => '1997-02-18', 'so_dien_thoai' => '0901111006', 'luong_co_ban' => 18000000],
            ['id_phong_ban' => 5, 'id_chuc_vu' => 5, 'ho_va_ten' => 'Bùi Quốc Khánh',   'email' => 'khanh.bui@recuai.vn',  'ngay_sinh' => '1993-09-25', 'so_dien_thoai' => '0901111007', 'luong_co_ban' => 17000000],
            ['id_phong_ban' => 6, 'id_chuc_vu' => 6, 'ho_va_ten' => 'Nguyễn Ngọc Lam',  'email' => 'lam.nguyen@recuai.vn',  'ngay_sinh' => '2000-12-01', 'so_dien_thoai' => '0901111008', 'luong_co_ban' => 10000000],
        ];

        foreach ($data as $item) {
            DB::table('nhan_viens')->insertOrIgnore(array_merge($item, [
                'password'   => Hash::make('password123'),
                'dia_chi'    => 'Đà Nẵng, Việt Nam',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
