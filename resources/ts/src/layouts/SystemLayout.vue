<template>
    <div class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">
        <router-view></router-view>
    </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
export default defineComponent({
    name: "system-layout",
    setup(){
        
        return {
        }
    }
});
</script>
<style scoped>.my-auto {
    margin-top: auto;
    margin-bottom: auto;
}

.custom-text-header {
    font-size: 8rem;
    line-height: 1;
    --tw-text-opacity: 1;
    color: rgb(209 213 219 / var(--tw-text-opacity));
}

.custom-text-sub-header {
    font-size: 3.75rem;
    line-height: 1;
    --tw-text-opacity: 1;
    color: rgb(209 213 219 / var(--tw-text-opacity));
}

.custom-text {
    font-size: 1.5rem;
    line-height: 2rem;
    --tw-text-opacity: 1;
    color: rgb(209 213 219 / var(--tw-text-opacity));
}

.items-end {
    align-items: flex-end;
}

.flex-row {
    flex-direction: row;
}

.justify-center {
    justify-content: center;
}

.lg\:w-1\/2 {
    width: 50%;
}

.text-center {
    text-align: center;
}

.h-screen {
    height: 100vh;
}</style>