<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TieuChiKpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'ten_tieu_chi' => 'Hoàn thành công việc đúng hạn',
                'mo_ta' => 'Tỷ lệ % task hoàn thành đúng deadline trong tháng',
                'trong_so' => 30.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'Chất lượng công việc',
                'mo_ta' => 'Đánh giá chất lượng đầu ra so với yêu cầu đặt ra',
                'trong_so' => 25.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'Chuyên cần và đúng giờ',
                'mo_ta' => 'Tỷ lệ ngày công thực tế / ngày công yêu cầu trong tháng',
                'trong_so' => 20.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'Tinh thần học hỏi và phát triển',
                'mo_ta' => 'Tham gia đào tạo, tự học và chia sẻ kiến thức nội bộ',
                'trong_so' => 10.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'Khả năng làm việc nhóm',
                'mo_ta' => 'Hợp tác, hỗ trợ đồng nghiệp và đóng góp cho nhóm',
                'trong_so' => 10.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'Sáng kiến và đổi mới',
                'mo_ta' => 'Đề xuất cải tiến quy trình hoặc giải pháp mới',
                'trong_so' => 5.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'Hài lòng khách hàng nội bộ',
                'mo_ta' => 'Mức độ hài lòng của các phòng ban liên quan (khảo sát)',
                'trong_so' => 5.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
            [
                'ten_tieu_chi' => 'KPI doanh số (Kinh doanh)',
                'mo_ta' => 'Tỷ lệ đạt target doanh thu tháng — áp dụng phòng KD',
                'trong_so' => 50.0,
                'muc_tieu' => 100.0,
                'don_vi' => '%',
                'tinh_trang' => 1
            ],
        ];

        foreach ($data as $item) {
            DB::table('tieu_chi_kpis')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
