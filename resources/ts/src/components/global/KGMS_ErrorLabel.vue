<template v-if="errors">
  <span v-for="(error, index) in errors" :key="index" class="block pr-4 mb-1 text-sm font-bold text-left text-red-600">
    {{ error }}
  </span>
</template>

<style scoped lang="scss"></style>

<script lang="ts">
import { defineComponent, ref, reactive } from "vue";

export default defineComponent({
    name: "KGMS_ErrorLable",
    props: {
        errors: Array
    },
    setup(props){
        return {}
    }

});
</script>