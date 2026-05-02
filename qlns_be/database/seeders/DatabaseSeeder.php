<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            ChucVuSeeder::class,
            PhongBanSeeder::class,
            NhanVienSeeder::class,
            LoaiHopDongSeeder::class,
            ChiTietHopDongSeeder::class,
            ChamCongSeeder::class,
            DonNghiPhepSeeder::class,
            ViTriTuyenDungSeeder::class,
            UngVienSeeder::class,
            HoSoUngTuyenSeeder::class,
            TieuChiDanhGiaTdSeeder::class,
            DanhGiaUngVienSeeder::class,
            TieuChiKpiSeeder::class,
            KpiNhanVienSeeder::class,
            QuyDinhChoDiemSeeder::class,
            ThuongVaPhatSeeder::class,
            PhanQuyenSeeder::class,
            LuongSeeder::class,
        ]);
    }
}
