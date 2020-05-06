<template>
  <div class="alert alert-success alert-flash" role="alert" v-show="show">
    <!-- <h4 class="alert-heading">Success!</h4> -->
  <strong>Success! </strong>{{body}}
  </div>
</template>

<script>
export default {
  props: ['message'],

  data(){
    return {
      body: this.message,
      show: this.show,
    }
  },

  created(){
    if(this.message){
      this.flash(this.message)
    }

    window.events.$on('flash', message => this.flash(message));
  },

  methods: {
    flash(message){
      this.show = true;
      this.body = message;

      this.hide(3000);
    },

    hide(timeout){
      setTimeout(() => this.show = false, timeout)
    }
  }

};
</script>

<style>
  .alert-flash{
    position: fixed;
    bottom: 25px;
    right: 25px;
  }
</style>