
<template>
  <div>
    <div v-for="(reply, index) in items">
      <reply :data="reply" @deleted="remove(index)"></reply>
    </div>
    <new-reply @created="add" :endpoint="endpoint"></new-reply>
  </div>
</template>

<script>
import Reply from "./Reply.vue";
import NewReply from '../components/NewReply';

export default {
  props: ["data"],

  components: { Reply, NewReply},

  data() {
    return {
      items: this.data,
      endpoint: `${location.pathname}/replies`
    };
  },

  methods: {
    remove(index) {
      this.items.splice(index, 1);
      flash("Reply Deleted!");
      this.$emit("removed");
    },
    add(reply) {
      this.items.push(reply);
      this.$emit("added");
    }
  }
};
</script>

<style>
</style>