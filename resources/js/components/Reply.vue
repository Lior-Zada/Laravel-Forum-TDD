<template>
  <!-- : will treat it as JSON -->
  <div :id="'reply-' + id" class="card mb-3">
    <div class="card-header">
      <div class="level">
        <div class="flex">
          <a :href="`/profile/${data.owner.name}`" v-text="data.owner.name"></a>
          replied
          <span v-text="ago"></span>
        </div>

        <favorite :reply="data" v-if="signedIn"></favorite>

        <!-- Added Vue component instead -->
        <!-- @auth -->
        <!-- <form action="{{url('replies/' .$reply->id . '/favorites')}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm" {{$reply->isFavorited() ? 'disabled':''}}>
                        {{$reply->favorites_count}} {{Str::plural('favorite', $reply->favorites_count)}}
                    </button>
        </form>-->
        <!-- @endauth -->
      </div>
    </div>
    <div class="card-body">
      <div v-if="editing">
        <form @submit.prevent="update">
          <div class="form-group">
            <textarea name="body" id="body" class="form-control" v-model="body" required></textarea>
          </div>
          <div class="level">
            <button class="btn btn-primary btn-sm" type="submit">Update</button>
            <button class="btn btn-link" @click="editing = false" type="button">Cancel</button>
          </div>
        </form>
      </div>
      <div v-else v-text="body"></div>
    </div>

    <!-- @can('update', $reply) -->
    <div class="card-footer level" v-if="canUpdate">
      <button class="btn btn-primary btn-sm mr-3" @click="editing = true">Edit reply</button>
      <button class="btn btn-danger btn-sm" @click="destroy">Delete reply</button>
    </div>
    <!-- @endcan -->
  </div>
</template>
<script>
// Because we use <favorite> within <reply> we import it here instead as global
import Favorite from "./Favorite.vue";
import moment from "moment";

export default {
  props: ["data"],

  components: { Favorite },

  computed: {
    signedIn() {
      return window.App.signedIn;
    },
    canUpdate() {
      return this.authorize(user => this.data.user_id == window.App.user.id);
      // return this.data.user_id == window.App.user.id;
    },
    ago() {
      return moment(this.data.created_at).fromNow() + "...";
    }
  },

  data() {
    return {
      editing: false,
      body: this.data.body,
      id: this.data.id
    };
  },

  methods: {
    update() {
      axios
        .patch(`/replies/${this.data.id}`, {
          body: this.body
        })
        .then(response => {
          this.editing = false;
          flash("Reply updated!");
        })
        .catch(error => {
          flash(error.response.data, "danger");
        });
    },

    destroy() {
      axios.delete(`/replies/${this.data.id}`);

      // The child communicated to his parent with EVENTS.
      // The deleted event needs to be listened to on the parent.
      // It will make the parent rerender
      this.$emit("deleted", this.data.id);

      // $(this.$el).fadeOut(300, () => {
      //   flash("Reply Deleted!");
      // });
    }
  }
};
</script>
