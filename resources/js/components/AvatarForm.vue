<template>
  <div class="card-header">
    <div class="level mb-3">
      <img :src="avatar" width="50" height="50" class="mr-3"/>

      <h1 v-text="user.name">
        <small v-text="ago"></small>
      </h1>
    </div>

    <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
      <!-- You can pass along native attributes to be assigned on the element within, which enables more flexability -->
      <image-upload name="avatar" @loaded="onLoad"></image-upload>
    </form>
  </div>
</template>

<script>
import moment from "moment";
import imageUpload from "./ImageUpload";
export default {
  props: ["user"],

  components: { imageUpload },

  data() {
    return {
      avatar: this.user.avatar_path
    };
  },

  computed: {
    canUpdate() {
      return this.authorize(user => user.id === this.user.id);
    },
    ago() {
      return "Joined" + moment(this.user.created_at).fromNow() + "...";
    }
  },

  methods: {
    getRoute() {
      return "";
    },

    onLoad(avatar) {
      this.updateCurrentImage(avatar.src);
      this.persist(avatar.file);
    },

    updateCurrentImage(image) {
      this.avatar = image;
    },

    persist(file) {
      let formData = new FormData();
      formData.append("avatar", file);
      axios
        .post(`/api/users/${this.user.name}/avatar`, formData)
        .then(response => flash("Avatar uploaded!"));
    }
  }
};
</script> 

<style>
</style>