<template>
  <!-- : will treat it as JSON -->
  <div :id="'reply-' + id" class="card mb-3" :class="isBest ? 'text-white bg-dark' : ''">
    <div class="card-header">
      <div class="level">
        <div class="flex">
          <a :href="`/profiles/${reply.owner.name}`" v-text="reply.owner.name"></a>
          replied
          <span v-text="ago"></span>
        </div>

        <favorite :reply="reply" v-if="signedIn"></favorite>

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
            <wysiwyg name="body" v-model="body" ></wysiwyg>
            <!-- <textarea name="body" id="body" class="form-control" v-model="body" required></textarea> -->
          </div>
          <div class="level">
            <button class="btn btn-primary btn-sm" type="submit">Update</button>
            <button class="btn btn-link" @click="editing = false" type="button">Cancel</button>
          </div>
        </form>
      </div>
      <div v-else v-html="body"></div>
    </div>

    <!-- @can('update', $reply) -->
    <div class="card-footer level" v-if="authorize('owns', reply) || authorize('markBestReply', reply)">
      <div v-if="authorize('owns', reply)">
        <button class="btn btn-primary btn-sm mr-3" @click="editing = true">Edit reply</button>
        <button class="btn btn-danger btn-sm" @click="destroy">Delete reply</button>  
      </div>
      
      <div class="ml-auto" v-if="authorize('markBestReply', reply)">
        <button class="btn btn-secondary btn-sm" @click="markBestReply" v-show="! isBest">Best reply</button>
      </div>
    </div>
    <!-- @endcan -->
  </div>
</template>
<script>
// Because we use <favorite> within <reply> we import it here instead as global
import Favorite from "./Favorite.vue";
import moment from "moment";

export default {
  props: ["reply"],

  components: { Favorite },

  computed: {
    ago() {
      return moment(this.reply.created_at).fromNow() + "...";
    }
  },

  data() {
    return {
      editing: false,
      body: this.reply.body,
      id: this.reply.id,
      isBest: this.reply.isBest,
    };
  },

  created(){
    // let all Reply components listen and decide wether they are best reply or not
    window.events.$on('best-reply-selected', id => this.isBest = (id == this.id))
  },

  methods: {
    update() {
      axios
        .patch(`/replies/${this.id}`, {
          body: this.body
        })
        .then(response => {
          this.editing = false;
          flash("Reply updated!");
        })
        .catch(error => flash(error.response.data, "danger"));
    },

    destroy() {
      axios.delete(`/replies/${this.id}`);

      // The child communicated to his parent with EVENTS.
      // The deleted event needs to be listened to on the parent.
      // It will make the parent rerender
      this.$emit("deleted", this.id);

      // $(this.$el).fadeOut(300, () => {
      //   flash("Reply Deleted!");
      // });
    },

    markBestReply() {
      axios
        .post(`/replies/${this.id}/best`)
        .then(response => {
          flash("Reply marked as best reply!");
          window.events.$emit('best-reply-selected', this.id);
        })
        .catch(error => flash(error.response.data, "danger"));
    }
  }
};
</script>
