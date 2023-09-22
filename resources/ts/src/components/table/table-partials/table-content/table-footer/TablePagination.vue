<template>
  <div class="flex flex-row-reverse basis-1/2">
    <nav aria-label="Page navigation example">
      <ul class="flex items-center -space-x-px h-8 text-sm">
        <li :class="{ disabled: isInFirstPage }" :style="{ cursor: !isInFirstPage ? 'pointer' : 'auto' }">
          <a @click="onClickFirstPage" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">First Page</span>
            <fa icon="angle-double-left"></fa>
          </a>
        </li>
        <li :class="{ disabled: isInFirstPage }" :style="{ cursor: !isInFirstPage ? 'pointer' : 'auto' }">
          <a @click="onClickPreviousPage" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Previous</span>
            <fa icon="angle-left"></fa>
          </a>
        </li>
        <li 
          v-for="(page, i) in pages" 
          :class="{ active: isPageActive(page.name), }" 
          :style="{ cursor: !page.isDisabled ? 'pointer' : 'auto' }" 
          :key="i"
        >
          <a @click="onClickPage(page.name)" :class="{'text-blue-600': isPageActive(page.name),'bg-blue-50': isPageActive(page.name) }" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page.name }}</a>
        </li>
        <li :class="{ disabled: isInLastPage }" :style="{ cursor: !isInLastPage ? 'pointer' : 'auto' }">
          <a @click="onClickNextPage" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Previous</span>
            <fa icon="angle-right"></fa>
          </a>
        </li>
        <li :class="{ disabled: isInLastPage }" :style="{ cursor: !isInLastPage ? 'pointer' : 'auto' }">
          <a @click="onClickLastPage" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Last Page</span>
            <fa icon="angle-double-right"></fa>
          </a>
        </li>
      </ul>
    </nav>
    
  </div>
</template>

<script lang="ts">
import { getAssetPath } from "@/core/helpers/assets";
import { computed, defineComponent } from "vue";

export default defineComponent({
  name: "table-pagination",
  components: {},

  props: {
    maxVisibleButtons: {
      type: Number,
      required: false,
      default: 5,
    },
    totalPages: {
      type: Number,
      required: true,
    },
    total: {
      type: Number,
      required: true,
    },
    perPage: {
      type: Number,
      required: true,
    },
    currentPage: {
      type: Number,
      required: true,
    },
  },
  emits: ["page-change"],
  setup(props, { emit }) {
    const startPage = computed(() => {
      if (
        props.totalPages < props.maxVisibleButtons ||
        props.currentPage === 1 ||
        props.currentPage <= Math.floor(props.maxVisibleButtons / 2) ||
        (props.currentPage + 2 > props.totalPages &&
          props.totalPages === props.maxVisibleButtons)
      ) {
        return 1;
      }

      if (props.currentPage + 2 > props.totalPages) {
        return props.totalPages - props.maxVisibleButtons + 1;
      }

      return props.currentPage - 2;
    });

    const endPage = computed(() => {
      return Math.min(
        startPage.value + props.maxVisibleButtons - 1,
        props.totalPages
      );
    });

    const pages = computed(() => {
      const range: Array<{
        name: number;
        isDisabled: boolean;
      }> = [];

      for (let i = startPage.value; i <= endPage.value; i += 1) {
        range.push({
          name: i,
          isDisabled: i === props.currentPage,
        });
      }

      return range;
    });

    const isInFirstPage = computed(() => {
      return props.currentPage === 1;
    });
    const isInLastPage = computed(() => {
      return props.currentPage === props.totalPages;
    });

    const onClickFirstPage = () => {
      emit("page-change", 1);
    };
    const onClickPreviousPage = () => {
      emit("page-change", props.currentPage - 1);
    };
    const onClickPage = (page: number) => {
      emit("page-change", page);
    };
    const onClickNextPage = () => {
      emit("page-change", props.currentPage + 1);
    };
    const onClickLastPage = () => {
      emit("page-change", props.totalPages);
    };
    const isPageActive = (page: number) => {
      return props.currentPage === page;
    };

    return {
      startPage,
      endPage,
      pages,
      isInFirstPage,
      isInLastPage,
      onClickFirstPage,
      onClickPreviousPage,
      onClickPage,
      onClickNextPage,
      onClickLastPage,
      isPageActive,
      getAssetPath,
    };
  },
});
</script>

<style lang="scss">
.pagination .page-item .page-link i {
  font-size: 1.5rem;
}
</style>
