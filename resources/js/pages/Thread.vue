<script>
import Replies from "../components/Replies";
import SubscribeButton from "../components/SubscribeButton";

export default {
  props: ["thread"],

  components: { Replies, SubscribeButton },

  data() {
    return {
      repliesCount: this.thread.replies_count,
      locked: this.thread.locked,
      title: this.thread.title,
      body: this.thread.body,
      form: {}, 
      editing: false,
    };
  },

  created(){
    this.resetForm();
  },

  methods: {
    // There are multiple ways to implement this.
    // 1. Send it from thread to replies as prop - can get ugly
    // 2. Register an event that will fire when clicked and listned to on the child component.
    // 3. (Store pattern) Shared state, create a custom module called ThreadStore which is the single source of truth, both thread and replies components will reference it.
    // 4. use VueX - formalized design pattern
    // 5. use the $parent.locked on the replies comp, it will then take the locked state from thread.
    lockThread() {
      this.locked = true;
      window.events.$emit("toggleLocked"); // update child
      this.persistLock();
    },

    unlockThread() {
      this.locked = false;
      window.events.$emit("toggleLocked"); // update child
      this.persistUnlock();
    },

    persistLock() {
      axios
        .post(`/locked-threads/${this.thread.slug}`)
        .then(() => flash("Thread locked!"));
    },

    persistUnlock() {
      axios
        .delete(`/locked-threads/${this.thread.slug}`)
        .then(() => flash("Thread unlocked!"));
    },

    update() {
      let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;
      axios.patch(uri, this.form).then(() => {
        flash("Thread updated!");
        this.editing = false;
        this.title = this.form.title;
        this.body = this.form.body;
      });
    },

    resetForm() {
      this.form.title = this.title;
      this.form.body = this.body;
      this.editing = false;
    }
  }
};
</script>