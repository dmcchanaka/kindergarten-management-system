<template>
    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
          <div class="flex-auto p-4">
            <div class="flex flex-row -mx-3 items-center">
                <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                        <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ translate(title) }}</p>
                        <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-to-tl bg-lime-500">
                            <fa :icon="icon" class="ni leading-none ni-money-coins text-lg relative top-2 text-white h-7" />
                        </div>
                    </div>
                </div>
                <div class="px-3 text-right basis-1/3">
                    <h1 class="mb-0 font-bold text-title">
                        {{ count }}
                    </h1>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, computed, onMounted, ref } from "vue";
import { useI18n } from "vue-i18n";
export default defineComponent({
  name: "info-card",
  props: {
    title: { type: String, required: true },
    icon: { type: String, required: true },
    count: { type: String, required: false },
  },
  setup() {
    const { t, te } = useI18n();

    const translate = (text: string) => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
        translate
    }
  }
});
</script>