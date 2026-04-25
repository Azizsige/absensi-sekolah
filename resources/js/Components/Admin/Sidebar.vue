<template>
    <n-layout-sider
        :native-scrollbar="false"
        collapse-mode="width"
        :collapsed-width="64"
        :collapsed="false"
        class="sidebar-container"
    >
        <div class="logo-container mb-4 flex w-full justify-start items-center">
            <div class="logo-bg">
                <img src="/images/logo.png" alt="logo" class="logo" />
            </div>
            <h1 class="main-title ml-2 font-bold text-lg">Absensi Web</h1>
        </div>

        <n-menu :options="menuOptions" />
    </n-layout-sider>
</template>

<script setup>
import { h } from "vue";
import { NIcon } from "naive-ui";
// Kita pakai Lucide Icon yang udah diinstall sebelumnya
import { LayoutDashboard, Users, UserCheck } from "lucide-vue-next";

// Fungsi pembantu buat render icon
const renderIcon = (icon) => {
    return () => h(NIcon, null, { default: () => h(icon) });
};

// Menu Navigasi Admin
const menuOptions = [
    {
        label: "Dashboard",
        key: "dashboard",
        icon: renderIcon(LayoutDashboard),
    },
    {
        label: "Data Master",
        key: "master",
        icon: renderIcon(Users),
        children: [
            { label: "Kelas & Jurusan", key: "kelas" },
            { label: "Data Siswa", key: "siswa" },
            { label: "Admin & Guru", key: "admin" },
        ],
    },
    {
        label: "Operasional",
        key: "operasional",
        icon: renderIcon(UserCheck),
        children: [
            { label: "Log Harian", key: "log" },
            { label: "Approval Izin", key: "approval" },
        ],
    },
];
</script>

<style lang="scss">
.sidebar-container {
    background-color: transparent;
    height: 100vh;
}
.logo-container {
    padding: 1.5rem 0.8rem 0.5rem 1.1rem;
    .logo-bg {
        width: 38px;
        height: 38px;
        display: flex;
        justify-content: center;
        align-items: center;
        .logo {
            width: 34px;
            object-fit: cover;
        }
    }
}
</style>
