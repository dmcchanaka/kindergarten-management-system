<template>
  <p class="text-red-500 text-xs italic -mb-3">{{ error }}</p>
</template>

<style scoped lang="scss"></style>

<script lang="ts">
import { defineComponent, ref, reactive } from "vue";

export default defineComponent({
    name: "global-error-lable",
    props: {
        error: String
    },
    setup(props){
        return {}
    }

});
</script>