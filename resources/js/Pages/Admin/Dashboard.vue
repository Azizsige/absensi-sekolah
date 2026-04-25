<template>
    <AdminLayout>
        <Head title="Dashboard Admin" />

        <div class="p-4">
            <n-card
                title="Selamat Datang, Senior Dev!"
                size="large"
                class="shadow-sm rounded-lg"
            >
                <p class="text-gray-600">
                    Kalau lu bisa liat kotak ini dengan rapi di sebelah kanan
                    Sidebar, berarti eksperimen Fase 1.5 kita SUKSES BESAR!
                    Inertia.js dan Naive UI udah berhasil nyatu.
                </p>

                <div class="mt-4">
                    <n-button type="primary">Tombol Tes Naive UI</n-button>
                </div>
            </n-card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AdminLayout from "../../Layouts/AdminLayout.vue";
</script>
