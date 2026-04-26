<template>
    <AdminLayout>
        <Head title="Approval Izin & Sakit" />

        <div class="p-4">
            <n-layout>
                <n-layout-content>
                    <div
                        class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
                    >
                        <n-space justify="space-between" class="mb-4">
                            <h2 class="text-lg font-bold text-gray-800">
                                Daftar Pengajuan Izin / Sakit
                            </h2>
                        </n-space>

                        <n-data-table
                            :columns="columns"
                            :data="leaveRequests.data"
                            :row-key="(row) => row.id"
                            :scroll-x="1000"
                            striped
                        />
                    </div>
                </n-layout-content>
            </n-layout>
        </div>
    </AdminLayout>
</template>

<script setup>
import { h } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { NButton, NIcon, NTag, NSpace, createDiscreteApi } from "naive-ui";
import { Check as CheckIcon, X as XIcon } from "lucide-vue-next";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

const { message, dialog } = createDiscreteApi(["message", "dialog"]);

// Tangkap props dari Controller
const props = defineProps({
    leaveRequests: Object,
});

// FUNGSI APPROVE / REJECT
const updateStatus = (row, newStatus) => {
    const isApprove = newStatus === "approved";
    const actionText = isApprove ? "menyetujui" : "menolak";

    const d = dialog.warning({
        title: "Konfirmasi Approval",
        content: `Yakin mau ${actionText} pengajuan ${row.type === "sick" ? "sakit" : "izin"} dari ${row.student.name}?`,
        positiveText: "Yakin!",
        negativeText: "Batal",
        closable: true,
        maskClosable: false,
        onPositiveClick: () => {
            // Trik loading dialog andalan kita
            d.loading = true;
            d.closable = false;
            d.negativeButtonProps = { disabled: true };

            return new Promise((resolve, reject) => {
                router.put(
                    `/admin/leave-requests/${row.id}/status`,
                    { status: newStatus }, // Kirim data status ke backend
                    {
                        preserveScroll: true,
                        onSuccess: () => {
                            message.success(
                                `Pengajuan berhasil di-${newStatus}!`,
                            );
                            resolve();
                        },
                        onError: () => {
                            message.error("Gagal memproses pengajuan bro!");
                            d.loading = false;
                            d.closable = true;
                            d.negativeButtonProps = { disabled: false };
                            reject();
                        },
                    },
                );
            });
        },
    });
};

// STRUKTUR KOLOM TABEL
const columns = [
    {
        title: "Siswa",
        key: "student.name",
        width: 200,
        render(row) {
            return h("div", [
                h("div", { class: "font-bold" }, row.student.name),
                h(
                    "div",
                    { class: "text-xs text-gray-500" },
                    `${row.student.classroom.name} (${row.student.classroom.major.code})`,
                ),
            ]);
        },
    },
    {
        title: "Jenis",
        key: "type",
        width: 100,
        render(row) {
            return h(
                NTag,
                {
                    type: row.type === "sick" ? "error" : "info",
                    size: "small",
                    round: true,
                },
                { default: () => (row.type === "sick" ? "Sakit" : "Izin") },
            );
        },
    },
    {
        title: "Tanggal",
        key: "dates",
        width: 200,
        render(row) {
            if (row.start_date === row.end_date) {
                return row.start_date; // Kalau cuma sehari
            }
            return `${row.start_date} s/d ${row.end_date}`; // Kalau berhari-hari
        },
    },
    { title: "Alasan", key: "reason", minWidth: 200 },
    {
        title: "Status",
        key: "status",
        width: 120,
        render(row) {
            let tagType = "warning";
            let tagText = "Pending";

            if (row.status === "approved") {
                tagType = "success";
                tagText = "Disetujui";
            }
            if (row.status === "rejected") {
                tagType = "error";
                tagText = "Ditolak";
            }

            return h(
                NTag,
                { type: tagType, size: "small" },
                { default: () => tagText },
            );
        },
    },
    {
        title: "Aksi",
        key: "actions",
        width: 120,
        render(row) {
            // Kalau statusnya BUKAN pending, gak usah tampilin tombol apa-apa
            if (row.status !== "pending") return "-";

            return h(
                NSpace,
                {},
                {
                    default: () => [
                        // Tombol Approve (Centang Hijau)
                        h(
                            NButton,
                            {
                                size: "small",
                                tertiary: true,
                                type: "success",
                                circle: true,
                                onClick: () => updateStatus(row, "approved"),
                            },
                            {
                                icon: () =>
                                    h(NIcon, null, {
                                        default: () => h(CheckIcon),
                                    }),
                            },
                        ),
                        // Tombol Reject (Silang Merah)
                        h(
                            NButton,
                            {
                                size: "small",
                                tertiary: true,
                                type: "error",
                                circle: true,
                                onClick: () => updateStatus(row, "rejected"),
                            },
                            {
                                icon: () =>
                                    h(NIcon, null, { default: () => h(XIcon) }),
                            },
                        ),
                    ],
                },
            );
        },
    },
];
</script>
