<template>
  <button @click="toggle" :class="classes">
    <i class="fas fa-heart"></i>
    <span v-text="favoritesCount"></span>
  </button>
</template>

<script>
export default {
  props: ["reply"],

  data() {
    return {
      favoritesCount: 0,
      isFavorited: false
    };
  },

  computed: {
    classes() {
      return [
        "btn",
        "btn-sm",
        this.isFavorited ? "btn-primary" : "btn-outline-primary"
      ];
    },
    endpoint() {
        return `/replies/${this.reply.id}/favorites`;
    }
  },

  created() {
    this.favoritesCount = this.reply.favoritesCount;
    this.isFavorited = this.reply.isFavorited;
  },

  methods: {
    toggle() {
      this.isFavorited ? this.unfavorite() : this.favorite();
    },

    unfavorite(){
        this.isFavorited = false;
        this.favoritesCount--;
        return axios.delete(this.endpoint);
    },

    favorite(){
        this.isFavorited = true;
        this.favoritesCount++;
        return axios.post(this.endpoint);
    },

  }
};
</script>
