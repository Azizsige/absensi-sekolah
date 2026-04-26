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

        <n-menu
            :options="menuOptions"
            :value="activeKey"
            :default-expanded-keys="expandedKeys"
            accordion
        />
    </n-layout-sider>
</template>

<script setup>
import { h, computed } from "vue";
import { NIcon } from "naive-ui";
// 1. Kita import tambahan icon buat sub-menu (Book, User, Shield, dll)
import {
    LayoutDashboard,
    Users,
    UserCheck,
    BookOpen,
    UserSquare,
    ShieldCheck,
    CalendarClock,
    ClipboardCheck,
} from "lucide-vue-next";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const renderIcon = (icon) => () => h(NIcon, null, { default: () => h(icon) });
const renderInertiaLink = (text, url) => () =>
    h(Link, { href: url }, { default: () => text });

// 2. Kita pasang property 'icon' di dalam children
const menuOptions = [
    {
        label: renderInertiaLink("Dashboard", "/"),
        key: "dashboard",
        icon: renderIcon(LayoutDashboard),
    },
    {
        label: "Data Master",
        key: "master",
        icon: renderIcon(Users),
        children: [
            {
                label: "Kelas & Jurusan",
                key: "kelas",
                icon: renderIcon(BookOpen), // Icon Kelas
            },
            {
                label: renderInertiaLink("Data Siswa", "/admin/students"),
                key: "students",
                icon: renderIcon(UserSquare), // Icon Siswa
            },
            {
                label: "Admin & Guru",
                key: "admin",
                icon: renderIcon(ShieldCheck), // Icon Admin
            },
        ],
    },
    {
        label: "Operasional",
        key: "operasional",
        icon: renderIcon(UserCheck),
        children: [
            // UBAH BARIS INI: Panggil fungsi renderInertiaLink
            {
                label: renderInertiaLink("Log Harian", "/admin/attendances"),
                key: "log",
                icon: renderIcon(CalendarClock),
            },
            {
                label: "Approval Izin",
                key: "approval",
                icon: renderIcon(ClipboardCheck),
            },
        ],
    },
];

const activeKey = computed(() => {
    if (page.url.startsWith("/admin/students")) return "students";
    if (page.url.startsWith("/admin/attendances")) return "log"; // <-- TAMBAHIN BARIS INI
    if (page.url === "/") return "dashboard";
    return null;
});

const expandedKeys = computed(() => {
    if (page.url.startsWith("/admin/")) return ["master"];
    return [];
});
</script>

<style lang="scss">
/* Style masih sama kayak sebelumnya */
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
