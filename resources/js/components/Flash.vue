<template>
  <div class="alert alert-flash" :class="'alert-' + level" role="alert" v-show="show" v-text="body"></div>
</template>

<script>
export default {
  props: ["message"],

  data() {
    return {
      body: this.message,
      level: "success",
      show: this.show
    };
  },

  created() {
    if (this.message) {
      this.flash();
    }

    window.events.$on("flash", data => this.flash(data));
  },

  methods: {
    flash(data) {
      if (data){
        this.body = data.message;
        this.level = data.level;
      }
      this.show = true;
      this.hide(3000);
    },

    hide(timeout) {
      setTimeout(() => (this.show = false), timeout);
    }
  }
};
</script>

<style>
.alert-flash {
  position: fixed;
  bottom: 25px;
  right: 25px;
}
</style>