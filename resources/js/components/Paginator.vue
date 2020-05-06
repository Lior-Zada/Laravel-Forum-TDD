<template>
  <ul class="pagination" v-if="shouldPaginate">
    <li class="page-item" v-show="prevUrl">
      <!-- Will click and preventDefault -->
      <a class="page-link" href="#" aria-label="Previous" @click.prevent="page--">
        <span aria-hidden="true">&laquo; Previous</span>
      </a>
    </li>

    <li class="page-item" v-show="nextUrl">
      <a class="page-link" href="#" aria-label="Next" @click.prevent="page++">
        <span aria-hidden="true">Next &raquo;</span>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: ["dataSet"],

  computed: {
    shouldPaginate() {
      return !!this.prevUrl || !!this.nextUrl;
    }
  },
  data() {
    return {
      page: 1,
      prevUrl: false,
      nextUrl: false
    };
  },

  watch: {
    dataSet() {
      this.page = this.dataSet.current_page;
      this.prevUrl = this.dataSet.prev_page_url;
      this.nextUrl = this.dataSet.next_page_url;
    },

    // Broadcast an event signaling the user has requested to view next\previous page.
    page() {
      this.broadcast().updateUrl();
    }
  },

  methods: {
    broadcast() {
      return this.$emit("pageChanged", this.page);
    },

    updateUrl() {
      history.pushState(null, null, this.preserveQueryString());
    },

    preserveQueryString() {
      return location.search.replace(/page=(\d+)/, `page=${this.page}`);
    }
  }
};
</script>

<style>
</style>