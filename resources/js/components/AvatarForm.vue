<template>
  <div class="card-header">
    <h1 v-text="user.name">
      <small v-text="ago"></small>
    </h1>

    <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
      <input type="file" name="avatar" id="avatar" accept="image/*" @change="onChange"/>
      <button type="submit">Add avatar</button>
    </form>

    <img :src="avatar" width="200" height="200" />
  </div>
</template>

<script>
import moment from "moment";
export default {
  props: ["user"],

  computed: {
    canUpdate() {
      return this.authorize(user => user.id === this.user.id);
    } ,  ago() {
      return ("Joined" + moment(this.user.created_at).fromNow() + "...");
    },
  },

  methods: {
 

    getRoute() {
      return "";
    },
    avatar() {
      return "";
    },
    onChange(e){
        
        if(! e.target.files.length) return;
        let file = e.target.files[0];
        
    }
  }
};
</script> 

<style>
</style>