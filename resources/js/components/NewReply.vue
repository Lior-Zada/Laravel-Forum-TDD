<template>
  <div>
    <div v-if="signedIn">
      <div class="form-group">
        <!-- <textarea
          name="body"
          id="body"
          class="form-control"
          placeholder="Write a reply..."
          rows="5"
          v-model="body"
        ></textarea>-->
        <wysiwyg
          name="body"
          v-model="body"
          placeholder="Write a reply..."
          :shouldChange="completed"
        ></wysiwyg>
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
import Tribute from "tributejs";

export default {
  data() {
    return {
      body: "",
      completed: false
    };
  },

  mounted() {
    this.activateTribute();
  },

  methods: {
    loadData(query, cb) {
      axios
        .get("/api/users", { params: { name: query } })
        .then(({ data }) => cb(data));
    },
    addReply() {
      axios
        .post(`${location.pathname}/replies`, { body: this.body })

        .then(response => {
          this.body = "";
          this.completed = true;

          flash("Your reply was posted successfully.");

          this.$emit("created", response.data);
        })

        .catch(error => {
          flash(error.response.data, "danger");
        });
    },

    activateTribute() {
      if (!window.App.signedIn) return;

      // https://github.com/zurb/tribute#a-collection
      let tribute = new Tribute({
        lookup: "value",
        fillAttr: "value",

        // Use Lodash debounce to create a delay between requests.
        values: _.debounce(this.loadData.bind(this), 750),

        // Dont display menu when there are no results.
        noMatchTemplate: function() {
          return '<span style:"visibility: hidden;"></span>';
        },

        // Start searching after 2 keystrokes
        menuShowMinLength: 2
      });

      tribute.attach($("trix-editor"));
      var editor = $("trix-editor")[0].editor;
      if (editor != null) {
        editor.composition.delegate.inputController.events.keypress = function() {};
        editor.composition.delegate.inputController.events.keydown = function() {};
      }
    }
  }
};
</script>

<style>
</style>