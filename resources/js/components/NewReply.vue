<template>
  <div>
    <div v-if="signedIn">
      <div class="form-group">
        <textarea
          name="body"
          id="body"
          class="form-control"
          placeholder="Write a reply..."
          rows="5"
          v-model="body"
        ></textarea>
      </div>

      <button type="submit" class="btn btn-lg btn-success" @click="addReply">Post</button>
    </div>

    <div v-else>
      <p class="text-center">
        Please
        <a href="/login">log</a> in to leave a reply...
      </p>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    signedIn() {
      return window.App.signedIn;
    }
  },
  data() {
    return {
      body: ""
    };
  },

  methods: {
    addReply() {
      axios
        .post(`${location.pathname}/replies`, { body: this.body })

        .then(response => {
          this.body = "";

          flash("Your reply was posted successfully.");

          this.$emit("created", response.data);
        })

        .catch(error => {
          flash(error.response.data, "danger");
        });
    }
  }
};
</script>

<style>
</style>