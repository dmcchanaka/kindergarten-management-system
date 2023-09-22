<template>
  <div
    class="flex flex-row basis-1/2"
  >
    <label for="items-per-page">
      <select
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        v-if="itemsPerPageDropdownEnabled"
        v-model="itemsCountInTable"
        name="items-per-page"
        id="items-per-page"
      >
        <option :value="5">5</option>
        <option :value="10">10</option>
        <option :value="25">25</option>
        <option :value="50">50</option>
      </select>
    </label>
  </div>
</template>

<script lang="ts">
import {
  computed,
  defineComponent,
  onMounted,
  ref,
  type WritableComputedRef,
} from "vue";

export default defineComponent({
  name: "table-items-per-page-select",
  components: {},
  props: {
    itemsPerPage: { type: Number, default: 10 },
    itemsPerPageDropdownEnabled: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  emits: ["update:itemsPerPage"],
  setup(props, { emit }) {
    const inputItemsPerPage = ref(10);

    onMounted(() => {
      inputItemsPerPage.value = props.itemsPerPage;
    });

    const itemsCountInTable: WritableComputedRef<number> = computed({
      get(): number {
        return props.itemsPerPage;
      },
      set(value: number): void {
        emit("update:itemsPerPage", value);
      },
    });

    return {
      itemsCountInTable,
    };
  },
});
</script>
