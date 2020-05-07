<template>
  <button :class="classes" @click="toggleSubscribe" v-text="buttonText"></button>
</template>

<script>
export default {
  props: ["active"],

  data(){
      return {
          isActive: this.active
      }
  },

  computed: {
    classes() {
      return ["btn", this.isActive ? "btn-success" : "btn-outline-secondary"];
    },
    buttonText(){
        return this.isActive ? "Unsubscribe" : "Subscribe";
    }
  },

  methods: {
    // when not logged in, you get error msg to console and nothing happens.

    toggleSubscribe() {
      let requestType = this.isActive ? "delete" : "post";
      let message = this.isActive ? "Unsubscribed" : "Subscribed";
      
      axios[requestType](`${location.pathname}/subscriptions`).then(() => {
        this.isActive = !this.isActive;
        flash(message);
      });
    }
  }
};
</script>

<style>
</style>