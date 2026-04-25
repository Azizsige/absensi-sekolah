<template>
    <n-config-provider>
        <n-notification-provider>
            <n-message-provider>
                <n-dialog-provider>
                    <n-layout has-sider position="absolute">
                        <Sidebar />

                        <n-layout :native-scrollbar="false" position="static">
                            <div
                                class="main-content flex-1 dark:bg-slate-800 dark:text-white my-2"
                            >
                                <Navbar />

                                <div class="relative h-full">
                                    <n-scrollbar>
                                        <div
                                            class="h-full overflow-auto md:mx-auto md:pb-18 p-3"
                                        >
                                            <slot />
                                        </div>
                                    </n-scrollbar>
                                </div>
                            </div>
                        </n-layout>
                    </n-layout>
                </n-dialog-provider>
            </n-message-provider>
        </n-notification-provider>
    </n-config-provider>
</template>

<script setup>
// Nanti kita bakal import Sidebar dan Navbar di sini
import { darkTheme } from "naive-ui";
import Sidebar from "../Components/Admin/Sidebar.vue";
import Navbar from "../Components/Admin/Navbar.vue";
</script>

<style lang="scss">
.n-layout {
    padding: 0 4px;
    background-color: transparent !important;
}

.dark {
    .main-content {
        --un-bg-opacity: 0.6;
        background: rgb(30 41 59 / var(--un-bg-opacity)) !important;
    }
}

.main-content {
    --un-bg-opacity: 0.4;
    background: #ffffffcc !important;
}
</style>
