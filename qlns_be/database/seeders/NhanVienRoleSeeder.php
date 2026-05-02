<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NhanVien;

class NhanVienRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NhanVien::query()->update(['role' => 'staff']);

        $this->command->info('Đã bỏ role cố định. Quyền ADMIN/HR được lấy từ bảng phan_quyens.');
    }
}
