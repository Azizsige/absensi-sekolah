<template>
    <AdminLayout>
        <Head title="Data Siswa" />

        <div class="p-4">
            <n-layout>
                <n-layout-content>
                    <n-space justify="space-between" class="mb-4 mt-2">
                        <n-input
                            placeholder="Cari NISN atau Nama..."
                            style="width: 250px"
                            clearable
                        >
                            <template #prefix
                                ><n-icon><SearchIcon /></n-icon
                            ></template>
                        </n-input>
                        <n-button type="primary" @click="createStudent">
                            <template #icon
                                ><n-icon><PlusIcon /></n-icon
                            ></template>
                            Tambah Siswa
                        </n-button>
                    </n-space>

                    <n-data-table
                        :columns="columns"
                        :data="students.data"
                        :row-key="rowKey"
                        :scroll-x="1000"
                        striped
                    />
                </n-layout-content>

                <n-drawer
                    v-model:show="showAddDialog"
                    :width="400"
                    placement="right"
                >
                    <n-drawer-content closable title="Form Data Siswa">
                        <n-form :model="form">
                            <n-form-item
                                label="NISN (10 Digit)"
                                path="nisn"
                                :feedback="form.errors.nisn"
                                :validation-status="
                                    form.errors.nisn ? 'error' : ''
                                "
                            >
                                <n-input
                                    v-model:value="form.nisn"
                                    placeholder="Masukkan NISN..."
                                    :allow-input="
                                        (value) => !value || /^\d+$/.test(value)
                                    "
                                    :maxlength="10"
                                    :disabled="isEditMode"
                                />
                            </n-form-item>

                            <n-form-item
                                label="Nama Lengkap"
                                path="name"
                                :feedback="form.errors.name"
                                :validation-status="
                                    form.errors.name ? 'error' : ''
                                "
                            >
                                <n-input
                                    v-model:value="form.name"
                                    placeholder="Masukkan Nama Lengkap..."
                                />
                            </n-form-item>

                            <n-form-item
                                label="Jenis Kelamin"
                                path="gender"
                                :feedback="form.errors.gender"
                                :validation-status="
                                    form.errors.gender ? 'error' : ''
                                "
                            >
                                <n-select
                                    v-model:value="form.gender"
                                    :options="genderOptions"
                                    placeholder="Pilih L/P"
                                />
                            </n-form-item>

                            <n-form-item
                                label="Kelas & Jurusan"
                                path="classroom_id"
                                :feedback="form.errors.classroom_id"
                                :validation-status="
                                    form.errors.classroom_id ? 'error' : ''
                                "
                            >
                                <n-select
                                    v-model:value="form.classroom_id"
                                    :options="classroomOptions"
                                    placeholder="Pilih Kelas..."
                                />
                            </n-form-item>

                            <div class="mt-4 flex justify-end">
                                <n-button
                                    type="primary"
                                    @click="submitForm"
                                    :loading="form.processing"
                                >
                                    Simpan Data
                                </n-button>
                            </div>
                        </n-form>
                    </n-drawer-content>
                </n-drawer>
            </n-layout>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, h, computed } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
// 1. Tambahin import createDiscreteApi dari naive-ui
import { NButton, NIcon, NTag, NSpace, createDiscreteApi } from "naive-ui";
import {
    Search as SearchIcon,
    Plus as PlusIcon,
    Pencil as EditIcon,
    Trash2 as DeleteIcon,
} from "lucide-vue-next";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
const { message, dialog } = createDiscreteApi(["message", "dialog"]);

const props = defineProps({
    students: Object,
    classrooms: Array,
});

const showAddDialog = ref(false);
const isEditMode = ref(false);
const editingId = ref(null);

const form = useForm({
    nisn: "",
    name: "",
    gender: null,
    classroom_id: null,
});

const genderOptions = [
    { label: "Laki-laki", value: "L" },
    { label: "Perempuan", value: "P" },
];

const classroomOptions = computed(() => {
    return props.classrooms.map((c) => ({
        label: `${c.name} (${c.major.code})`,
        value: c.id,
    }));
});

const createStudent = () => {
    isEditMode.value = false;
    editingId.value = null;
    form.reset();
    form.clearErrors();
    showAddDialog.value = true;
};

// Fungsi dipanggil saat tombol Pensil diklik
const editStudent = (row) => {
    isEditMode.value = true;
    editingId.value = row.id;

    // Isi form dengan data dari baris yang diklik
    form.nisn = row.nisn;
    form.name = row.name;
    form.gender = row.gender;
    form.classroom_id = row.classroom_id;

    form.clearErrors();
    showAddDialog.value = true; // Buka laci
};

// Fungsi dipanggil saat tombol Sampah diklik
// Fungsi dipanggil saat tombol Sampah diklik
const deleteStudent = (row) => {
    const d = dialog.warning({
        title: "Konfirmasi Hapus",
        content: `Yakin mau hapus data siswa bernama ${row.name}?`,
        positiveText: "Yakin, Hapus!",
        negativeText: "Batal",
        closable: true,
        maskClosable: false, // Biar nggak nutup pas klik luar
        onPositiveClick: () => {
            // MAGIC: Kita PAKSA tombol 'Yakin' masuk ke mode loading
            // Saat d.loading = true, tombol otomatis ter-disable dan muter spinner!
            d.loading = true;

            // Kunci dialog lainnya
            d.closable = false;
            d.negativeButtonProps = { disabled: true };

            return new Promise((resolve, reject) => {
                router.delete(`/admin/students/${row.id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        message.success("Data berhasil dihapus selamanya!");
                        resolve(); // Tutup dialog jika sukses
                    },
                    onError: () => {
                        message.error("Gagal menghapus data bro!");

                        // Kalau gagal, matikan loading dan buka lagi kunciannya
                        d.loading = false;
                        d.closable = true;
                        d.negativeButtonProps = { disabled: false };
                        reject(); // Biar dialog tetap terbuka
                    },
                });
            });
        },
    });
};

// 3. Kita tambahin message.success dan message.error di callback Inertia
const submitForm = () => {
    // Cegatan Frontend (Tetep kita pakai)
    if (!form.nisn || !form.name || !form.gender || !form.classroom_id) {
        message.warning("Eits, form-nya masih ada yang kosong tuh bro!");
        return;
    }

    if (form.nisn.length !== 10) {
        message.warning("NISN wajib 10 digit angka ya bro!");
        return;
    }

    if (isEditMode.value) {
        form.put(`/admin/students/${editingId.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showAddDialog.value = false;
                form.reset();
                message.success("Mantap! Data siswa berhasil diubah.");
            },
            onError: (errors) => {
                // Tampilkan error spesifik dari Laravel
                message.error(
                    errors.nisn || errors.name || "Gagal mengubah data bro!",
                );
            },
        });
    } else {
        form.post("/admin/students", {
            preserveScroll: true,
            onSuccess: () => {
                // Double check biar aman: Kalau object form.errors beneran kosong, baru bilang sukses
                if (Object.keys(form.errors).length === 0) {
                    showAddDialog.value = false;
                    form.reset();
                    message.success(
                        "Mantap! Data siswa baru berhasil ditambahkan.",
                    );
                }
            },
            onError: (errors) => {
                // Tampilkan error spesifik dari Laravel (misal: "NISN has already been taken")
                message.error(
                    errors.nisn || "Gagal menyimpan! Cek form-nya bro.",
                );
            },
        });
    }
};

const rowKey = (row) => {
    return row.id;
};

const columns = [
    { title: "NISN", key: "nisn", fixed: "left", width: 150 },
    { title: "Nama Lengkap", key: "name", minWidth: 200 },
    {
        title: "L/P",
        key: "gender",
        width: 100,
        render(row) {
            return h(
                NTag,
                {
                    type: row.gender === "L" ? "info" : "warning",
                    size: "small",
                    round: true,
                },
                {
                    default: () =>
                        row.gender === "L" ? "Laki-laki" : "Perempuan",
                },
            );
        },
    },
    {
        title: "Kelas",
        key: "classroom.name",
        render(row) {
            return row.classroom
                ? `${row.classroom.name} (${row.classroom.major.code})`
                : "-";
        },
    },
    {
        title: "Aksi",
        key: "actions",
        width: 150,
        render(row) {
            return h(
                NSpace,
                {},
                {
                    default: () => [
                        h(
                            NButton,
                            {
                                size: "small",
                                tertiary: true,
                                type: "info",
                                circle: true,
                                onClick: () => editStudent(row), // <-- TAMBAHIN INI
                            },
                            {
                                icon: () =>
                                    h(NIcon, null, {
                                        default: () => h(EditIcon),
                                    }),
                            },
                        ),
                        h(
                            NButton,
                            {
                                size: "small",
                                tertiary: true,
                                type: "error",
                                circle: true,
                                onClick: () => deleteStudent(row), // <-- TAMBAHIN INI
                            },
                            {
                                icon: () =>
                                    h(NIcon, null, {
                                        default: () => h(DeleteIcon),
                                    }),
                            },
                        ),
                    ],
                },
            );
        },
    },
];
</script>
