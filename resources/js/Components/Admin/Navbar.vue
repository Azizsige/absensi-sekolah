<template>
    <n-page-header class="px-2 py-3 navbar relative z-100 border-b">
        <template #title>
            <div class="flex items-center">
                <n-button mx-2 size="small" quaternary circle>
                    <span class="font-bold">☰</span>
                </n-button>
                <div class="ml-4 font-semibold text-gray-700">
                    Panel Admin Absensi
                </div>
            </div>
        </template>

        <template #extra>
            <div class="flex items-center gap-4 pr-4">
                <div class="user-info text-sm font-medium">Admin TU</div>
                <n-button size="small" type="error" dashed>Logout</n-button>
            </div>
        </template>
    </n-page-header>
</template>

<script setup>
// Script dikosongin dulu karena logika interaktifnya kita handle nanti
</script>

<style lang="scss">
.navbar {
    border-bottom: solid 1px #e5e7eb;
    padding-bottom: 0.8rem;
    padding-top: 0.8rem;
}
</style>
