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
        // Admin users (id 1, 2)
        NhanVien::whereIn('id', [1, 2])->update(['role' => 'admin']);

        // HR users (id 3, 4) - nếu có
        NhanVien::whereIn('id', [3, 4])->update(['role' => 'hr']);

        // Set remaining users as staff
        NhanVien::whereNotIn('id', [1, 2, 3, 4])->update(['role' => 'staff']);

        $this->command->info('Roles assigned successfully!');
    }
}
