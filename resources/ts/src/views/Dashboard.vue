<template>
  This is dashboard
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";
import { getAssetPath } from "@/core/helpers/assets";

export default defineComponent({
  name: "dashboard",
  setup() {
    return {
      getAssetPath
    }
  },
});
</script>