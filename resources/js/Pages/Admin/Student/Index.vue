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
                            <template #prefix>
                                <n-icon><SearchIcon /></n-icon>
                            </template>
                        </n-input>

                        <n-button type="primary" @click="createStudent">
                            <template #icon>
                                <n-icon><PlusIcon /></n-icon>
                            </template>
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
                    <n-drawer-content closable title="Tambah Data Siswa">
                        <p class="text-gray-500">
                            Form Tambah Siswa bakal kita rakit di sini nanti
                            bro!
                        </p>
                    </n-drawer-content>
                </n-drawer>
            </n-layout>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, h } from "vue";
import { Head } from "@inertiajs/vue3";
import { NButton, NIcon, NTag, NSpace } from "naive-ui";
import {
    Search as SearchIcon,
    Plus as PlusIcon,
    Pencil as EditIcon,
    Trash2 as DeleteIcon,
} from "lucide-vue-next";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

// 1. Tangkap props dari Controller
const props = defineProps({
    students: Object,
});

// 2. State untuk buka/tutup panel geser (Drawer)
const showAddDialog = ref(false);

// 3. Fungsi pembantu
function createStudent() {
    showAddDialog.value = true;
}

function rowKey(row) {
    return row.id;
}

// 4. Struktur Kolom Tabel yang dirapikan ala Yummy
const columns = [
    {
        title: "NISN",
        key: "nisn",
        fixed: "left", // Di-fix di kiri kalau di-scroll
        width: 150,
    },
    {
        title: "Nama Lengkap",
        key: "name",
        minWidth: 200,
    },
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
            // Tombol Edit & Delete pakai Icon
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
