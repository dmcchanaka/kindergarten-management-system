<template>
    <footer class="pt-4 fixed bottom-0">
        <div class="w-full px-6 mx-auto">
            <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
                <div class="leading-normal text-center text-sm text-slate-500 lg:text-left">
                        ©
                        made with <i class="fa fa-heart"></i> by
                        <a href="https://www.creative-tim.com" class="font-semibold text-slate-700" target="_blank">Creative
                            Tim</a>
                        for a better web.
                    </div>
            </div>
        </div>
    </footer>
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  name: "footer-layer",
  setup() {
    return {
    }
  },
});
</script>