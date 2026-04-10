<template>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center gap-2">
                <input v-model="search" @keyup.enter="applyFilters" class="form-control w-50"
                    placeholder="Tìm kiếm công việc">
                <select v-model="selectedRole" class="form-select w-auto">
                    <option :value="null">Tất cả chức vụ</option>
                    <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.ten_chuc_vu }}</option>
                </select>
                <button class="btn btn-primary" @click="applyFilters">Lọc</button>
                <button class="btn btn-outline-secondary" @click="resetFilters">Reset</button>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-3">
                <template v-if="filteredJobs.length">
                    <div class="col-lg-4 col-md-6" v-for="job in filteredJobs" :key="job.id || job._id || job.name">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ job.tieu_de || job.ten_cong_viec || job.title || job.name }}
                                </h5>
                                <p class="text-muted mb-1"><strong>Chức vụ:</strong> {{ displayRole(job) }}</p>
                                <p class="text-muted mb-1"><strong>Số lượng:</strong> {{ job.so_luong ?? job.muc_luong
                                    ?? '-' }}</p>
                                <p class="text-muted mb-1"><strong>Thời hạn:</strong> {{ formatDate(job.ngay_bat_dau) }}
                                    - {{ formatDate(job.ngay_ket_thuc) }}</p>
                                <p class="card-text text-truncate">{{ job.mo_ta || job.description || '-' }}</p>
                                <div class="mt-auto pt-2 d-flex justify-content-between">
                                    <small class="text-muted">{{ formatDate(job.created_at || job.createdAt ||
                                        job.ngay_bat_dau) }}</small>
                                    <button class="btn btn-sm btn-primary">Xem chi tiết</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="col-12 text-center py-5">Không có công việc nào phù hợp.</div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

const API = 'http://127.0.0.1:8000/api';

export default {
    data() {
        return {
            jobs: [],
            roles: [],
            selectedRole: null,
            search: '',
        };
    },
    computed: {
        filteredJobs() {
            let list = Array.isArray(this.jobs) ? this.jobs : [];
            if (this.selectedRole) {
                list = list.filter((j) => {
                    const id = j.id_chuc_vu ?? j.chuc_vu_id ?? j.role_id ?? null;
                    return id == this.selectedRole || (j.chuc_vu && j.chuc_vu.id == this.selectedRole);
                });
            }
            if (this.search && this.search.trim()) {
                const s = this.search.trim().toLowerCase();
                list = list.filter((j) => {
                    const hay = (j.ten_cong_viec || j.title || j.name || '').toString().toLowerCase();
                    const desc = (j.mo_ta || j.description || '').toString().toLowerCase();
                    return hay.includes(s) || desc.includes(s);
                });
            }
            return list;
        },
    },
    mounted() {
        this.loadJobs();
        this.loadRoles();
    },
    methods: {
        loadJobs(search = '') {
            const params = {};
            if (search && search.trim()) params.search = search.trim();
            axios.get(`${API}/vi-tri/open`, { params })
                .then((res) => {
                    this.jobs = Array.isArray(res.data) ? res.data : (res.data.data || res.data || []);
                })
                .catch(() => {
                    this.jobs = [];
                    this.$toast?.error('Không thể tải danh sách công việc.');
                });
        },
        loadRoles() {
            axios.get(`${API}/vi-tri/open`).then((res) => {
                this.roles = Array.isArray(res.data) ? res.data : (res.data.data || []);
            }).catch(() => {
                this.roles = [];
            });
        },
        applyFilters() {
            // fetch possibly filtered list from server (search param supported by getDataOpen)
            this.loadJobs(this.search);
        },
        resetFilters() {
            this.selectedRole = null;
            this.search = '';
        },
        displayRole(job) {
            if (!job) return '-';
            if (job.chuc_vu?.ten_chuc_vu) return job.chuc_vu.ten_chuc_vu;
            if (job.ten_chuc_vu) return job.ten_chuc_vu;
            const id = job.id_chuc_vu ?? job.chuc_vu_id ?? job.role_id ?? null;
            if (id) {
                const found = this.roles.find((r) => r.id === id);
                return found?.ten_chuc_vu ?? '-';
            }
            return '-';
        },
        formatDate(v) {
            if (!v) return '';
            const d = new Date(v);
            if (isNaN(d)) return v;
            return d.toLocaleDateString('vi-VN');
        },
    },
};
</script>

<style scoped>
.card .card-body {
    min-height: 170px;
}

.text-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
