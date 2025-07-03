<template>
    <nav class="flex justify-center items-center w-full mt-4">
      <ul class="inline-flex items-center space-x-2">
        <!-- Previous Button -->
        <li>
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-4 py-2 text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50">
            Previous
          </button>
        </li>

        <!-- Page Numbers -->
        <li v-for="page in totalPages" :key="page">
          <button
            @click="changePage(page)"
            :class="[
              'px-4 py-2 border border-gray-300 rounded-md',
              currentPage === page
                ? 'bg-gray-900 text-white'
                : 'bg-white text-gray-500 hover:bg-gray-100'
            ]">
            {{ page }}
          </button>
        </li>

        <!-- Next Button -->
        <li>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50">
            Next
          </button>
        </li>
      </ul>
    </nav>
  </template>

  <script>
  export default {
    props: {
      totalRecords: { type: Number, required: true }, // Total items (books, users, products)
      rowsPerPage: { type: Number, default: 10 }, // Items per page (default: 10)
      currentPage: { type: Number, default: 1 }, // Current active page
    },
    computed: {
      totalPages() {
        return Math.ceil(this.totalRecords / this.rowsPerPage); // Calculate total pages
      }
    },
    methods: {
      changePage(page) {
        if (page !== this.currentPage) {
          this.$emit("update:currentPage", page);
        }
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.$emit("update:currentPage", this.currentPage - 1);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.$emit("update:currentPage", this.currentPage + 1);
        }
      }
    }
  };
  </script>
