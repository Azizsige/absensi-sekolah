<template>
    <AdminLayout>
        <Head title="Log Harian (Absensi)" />

        <div class="p-4">
            <n-layout>
                <n-layout-content>
                    <div
                        class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-4"
                    >
                        <n-space align="center">
                            <span class="font-semibold text-gray-700"
                                >Filter Absensi:</span
                            >

                            <n-select
                                v-model:value="selectedClassroom"
                                :options="classroomOptions"
                                placeholder="Pilih Kelas..."
                                style="width: 250px"
                                @update:value="fetchStudents"
                            />

                            <n-date-picker
                                v-model:formatted-value="selectedDate"
                                value-format="yyyy-MM-dd"
                                type="date"
                                style="width: 200px"
                                @update:value="fetchStudents"
                            />
                        </n-space>
                    </div>

                    <n-spin :show="isLoading">
                        <div
                            v-if="selectedClassroom"
                            class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
                        >
                            <n-space justify="space-between" class="mb-4">
                                <h2 class="text-lg font-bold text-gray-800">
                                    Daftar Siswa
                                </h2>

                                <n-button
                                    type="primary"
                                    @click="submitAttendance"
                                    :loading="form.processing"
                                    :disabled="students.length === 0"
                                >
                                    <template #icon
                                        ><n-icon><SaveIcon /></n-icon
                                    ></template>
                                    Simpan Absensi Massal
                                </n-button>
                            </n-space>

                            <n-empty
                                v-if="students.length === 0"
                                description="Belum ada siswa di kelas ini bro!"
                                class="my-10"
                            />

                            <n-data-table
                                v-else
                                :columns="columns"
                                :data="students"
                                :row-key="(row) => row.id"
                                :scroll-x="800"
                                striped
                            />
                        </div>
                        <n-empty
                            v-else
                            description="Pilih kelas dulu di atas bro buat nampilin daftar siswa!"
                            class="my-20"
                        />
                    </n-spin>
                </n-layout-content>
            </n-layout>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, h, computed, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import {
    NButton,
    NIcon,
    NSpace,
    NSelect,
    NDatePicker,
    NEmpty,
    NRadioGroup,
    NRadioButton,
    createDiscreteApi,
} from "naive-ui";
import { Save as SaveIcon } from "lucide-vue-next";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

const { message } = createDiscreteApi(["message"]);

const isLoading = ref(false);

// 1. Tangkap Data dari Controller
const props = defineProps({
    classrooms: Array,
    students: Array,
    filters: Object,
});

// 2. State untuk Filter
const selectedClassroom = ref(
    props.filters.classroom_id ? Number(props.filters.classroom_id) : null,
);
const selectedDate = ref(props.filters.date);

// Mapping opsi kelas buat Naive UI
const classroomOptions = computed(() => {
    return props.classrooms.map((c) => ({
        label: `${c.name} (${c.major.code})`,
        value: c.id,
    }));
});

// 3. FUNGSI MAGIC: Ambil ulang data siswa tiap kali Kelas/Tanggal diganti
const fetchStudents = () => {
    if (!selectedClassroom.value) return;

    // Mulai Loading
    isLoading.value = true;

    router.get(
        "/admin/attendances",
        {
            classroom_id: selectedClassroom.value,
            date: selectedDate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            // Matikan loading kalau proses sudah selesai (entah sukses atau gagal)
            onFinish: () => {
                isLoading.value = false;
            },
        },
    );
};

// 4. SETUP FORM UNTUK MASS ASSIGNMENT
const form = useForm({
    date: selectedDate.value,
    attendances: [],
});

// MAGIC AUTO-FILL: Tiap kali data props.students berubah, kita siapin kotak kosong di form-nya
watch(
    () => props.students,
    (newStudents) => {
        if (newStudents) {
            form.date = selectedDate.value; // Samain tanggal form dengan filter

            form.attendances = newStudents.map((student) => {
                // Cek apakah hari ini siswa udah punya absen? Kalau iya ambil status lamanya, kalau belum default 'present'
                const todayRecord =
                    student.attendances && student.attendances.length > 0
                        ? student.attendances[0]
                        : null;

                return {
                    student_id: student.id,
                    status: todayRecord ? todayRecord.status : "present",
                };
            });
        }
    },
    { immediate: true },
);

// 5. KOLOM TABEL + INJEKSI RADIO BUTTON DI DALAMNYA
const columns = [
    { title: "NISN", key: "nisn", width: 120 },
    { title: "Nama Lengkap", key: "name", minWidth: 200 },
    {
        title: "Status Kehadiran",
        key: "status",
        width: 380,
        render(row, index) {
            // Kita gambar Radio Group Naive UI di dalam kolom tabel, lalu kita ikat (v-model) ke form.attendances[index]
            return h(
                NRadioGroup,
                {
                    value: form.attendances[index].status,
                    "onUpdate:value": (val) => {
                        form.attendances[index].status = val;
                    },
                },
                {
                    default: () => [
                        h(NRadioButton, { value: "present", label: "Hadir" }),
                        h(NRadioButton, { value: "late", label: "Telat" }),
                        h(NRadioButton, { value: "sick", label: "Sakit" }),
                        h(NRadioButton, { value: "leave", label: "Izin" }),
                        h(NRadioButton, { value: "alpha", label: "Alpha" }),
                    ],
                },
            );
        },
    },
];

// 6. SUBMIT MASSAL KE BACKEND
const submitAttendance = () => {
    form.post("/admin/attendances", {
        preserveScroll: true,
        onSuccess: () => {
            message.success(
                `Mantap! Absensi kelas untuk tanggal ${selectedDate.value} berhasil disimpan.`,
            );
        },
        onError: () => {
            message.error("Waduh, ada yang gagal nih. Coba cek lagi bro!");
        },
    });
};
</script>
