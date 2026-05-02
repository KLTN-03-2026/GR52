<template>
    <div class="cv-analysis card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-1">
                    <i class="fa-solid fa-wand-magic-sparkles me-2 text-primary"></i>AI Phân Tích CV
                </h5>
                <small class="text-muted">Chấm điểm và xếp hạng ứng viên theo vị trí tuyển dụng</small>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" :disabled="loading || analyzing" @click="loadAnalyses">
                    <i class="fa-solid fa-rotate me-1"></i>Làm mới
                </button>
                <button class="btn btn-primary btn-sm" :disabled="analyzing" @click="analyzeBatch">
                    <span v-if="analyzing" class="spinner-border spinner-border-sm me-1"></span>
                    <i v-else class="fa-solid fa-brain me-1"></i>Phân tích tất cả
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="metric">
                        <span>Đã phân tích</span>
                        <strong>{{ analyses.length }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric">
                        <span>Điểm cao nhất</span>
                        <strong>{{ bestScore }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric">
                        <span>Phù hợp</span>
                        <strong>{{ recommendedCount }}</strong>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center py-4">
                <div class="spinner-border spinner-border-sm me-2"></div>Đang tải kết quả AI...
            </div>

            <div v-else-if="analyses.length === 0" class="empty-state">
                <i class="fa-solid fa-file-circle-question"></i>
                <p>Chưa có kết quả phân tích AI cho vị trí này.</p>
            </div>

            <div v-else class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="22%">Ứng viên</th>
                            <th width="12%">Điểm</th>
                            <th width="15%">Khuyến nghị</th>
                            <th width="26%">Tóm tắt</th>
                            <th width="20%">Kỹ năng phù hợp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in analyses" :key="item.id">
                            <td>{{ index + 1 }}</td>
                            <td>
                                <strong>{{ item.ung_vien?.ho_ten || 'N/A' }}</strong>
                                <div class="text-muted small">{{ item.ung_vien?.email || 'N/A' }}</div>
                            </td>
                            <td>
                                <span :class="scoreClass(item.tong_diem)">{{ item.tong_diem }}/100</span>
                            </td>
                            <td>
                                <span :class="recommendationClass(item.khuyến_nghị)">
                                    {{ item.khuyến_nghị || 'Cần xem xét' }}
                                </span>
                            </td>
                            <td>
                                <div class="summary-text">{{ item.tóm_tắt_phân_tích || '-' }}</div>
                            </td>
                            <td>
                                <span v-for="skill in limitedSkills(item.kỹ_năng_phù_hợp)" :key="skill" class="skill-chip">
                                    {{ skill }}
                                </span>
                                <span v-if="!item.kỹ_năng_phù_hợp?.length" class="text-muted">-</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { createToaster } from '@meforma/vue-toaster';

const toaster = createToaster({ position: 'top-right' });
const API = 'http://127.0.0.1:8000/api';

export default {
    name: 'CVAnalysis',
    props: {
        viTriTuyenDungId: {
            type: [String, Number],
            required: true,
        },
    },
    data() {
        return {
            analyses: [],
            loading: false,
            analyzing: false,
        };
    },
    computed: {
        bestScore() {
            if (!this.analyses.length) return 0;
            return Math.max(...this.analyses.map((item) => Number(item.tong_diem) || 0));
        },
        recommendedCount() {
            return this.analyses.filter((item) => item.khuyến_nghị === 'Phù hợp').length;
        },
    },
    watch: {
        viTriTuyenDungId: {
            immediate: true,
            handler() {
                this.loadAnalyses();
            },
        },
    },
    methods: {
        authHeaders() {
            return {
                Authorization: `Bearer ${localStorage.getItem('tk_nhan_vien')}`,
            };
        },
        async loadAnalyses() {
            if (!this.viTriTuyenDungId) return;

            this.loading = true;
            try {
                const response = await axios.get(`${API}/admin/cv-analysis/job/${this.viTriTuyenDungId}`, {
                    headers: this.authHeaders(),
                });
                this.analyses = response.data.data || [];
            } catch (error) {
                if (error.response?.status !== 404) {
                    toaster.error('Không thể tải kết quả phân tích AI.');
                }
                this.analyses = [];
            } finally {
                this.loading = false;
            }
        },
        async analyzeBatch() {
            if (!this.viTriTuyenDungId) return;

            this.analyzing = true;
            try {
                const response = await axios.post(
                    `${API}/admin/cv-analysis/analyze-batch`,
                    { vi_tri_tuyen_dung_id: this.viTriTuyenDungId },
                    { headers: this.authHeaders() },
                );
                this.analyses = response.data.data || [];
                toaster.success(response.data.message || 'Phân tích CV thành công.');
            } catch (error) {
                toaster.error(error.response?.data?.message || 'Phân tích AI thất bại.');
            } finally {
                this.analyzing = false;
            }
        },
        limitedSkills(skills) {
            return Array.isArray(skills) ? skills.slice(0, 4) : [];
        },
        scoreClass(score) {
            if (score >= 80) return 'score score-good';
            if (score >= 60) return 'score score-mid';
            return 'score score-low';
        },
        recommendationClass(value) {
            if (value === 'Phù hợp') return 'badge bg-success';
            if (value === 'Không phù hợp') return 'badge bg-danger';
            return 'badge bg-warning text-dark';
        },
    },
};
</script>

<style scoped>
.cv-analysis {
    border-radius: 8px;
}

.metric {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
}

.metric span {
    color: #6c757d;
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
}

.metric strong {
    color: #212529;
    font-size: 1.6rem;
}

.empty-state {
    align-items: center;
    color: #6c757d;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    justify-content: center;
    min-height: 160px;
}

.empty-state i {
    font-size: 2rem;
}

.summary-text {
    display: -webkit-box;
    line-clamp: 3;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    max-width: 420px;
    overflow: hidden;
}

.score {
    border-radius: 999px;
    display: inline-block;
    font-weight: 700;
    min-width: 72px;
    padding: 0.35rem 0.65rem;
    text-align: center;
}

.score-good {
    background: #d1e7dd;
    color: #0f5132;
}

.score-mid {
    background: #fff3cd;
    color: #664d03;
}

.score-low {
    background: #f8d7da;
    color: #842029;
}

.skill-chip {
    background: #eef2ff;
    border: 1px solid #dbe3ff;
    border-radius: 999px;
    color: #3851a3;
    display: inline-block;
    font-size: 0.78rem;
    font-weight: 600;
    margin: 0 0.35rem 0.35rem 0;
    padding: 0.25rem 0.55rem;
}
</style>
