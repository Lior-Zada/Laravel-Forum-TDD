
<template>
  <div>
    <div v-for="(reply, index) in items" :key="reply.id">
      <reply :reply="reply" @deleted="remove(index)"></reply>
    </div>

    <paginator :dataSet="dataSet" @pageChanged="fetch"></paginator>

    <new-reply @created="add"></new-reply>
  </div>
</template>

<script>
import Reply from "./Reply.vue";
import NewReply from "../components/NewReply";
import collection from "../mixins/collection";

export default {
  // to use the mixins abilities
  mixins: [collection],
  components: { Reply, NewReply },

  data() {
    return {
      dataSet: {},
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch(page) {
      axios.get(this.url(page)).then(this.refresh);
    },

    url(page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/);

        page = query ? query[1] : null
      }

      return `${window.location.pathname}/replies?page=${page}`;
    },

    refresh({ data }) {
      this.dataSet = data;
      this.items = data.data;
      window.scrollTo(0,0);
    }
  }
};
</script>

<style>
</style>