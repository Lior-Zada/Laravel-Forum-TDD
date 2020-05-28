<script>
import Replies from '../components/Replies';
import SubscribeButton from '../components/SubscribeButton';

export default {
    props: ['dataRepliesCount', 'dataLocked'],

    components: {Replies, SubscribeButton},

    data(){
        return {
            repliesCount: this.dataRepliesCount, 
            locked: this.dataLocked,
        }
    },

    methods:{
        // There are multiple ways to implement this.
        // 1. Send it from thread to replies as prop - can get ugly
        // 2. Register an event that will fire when clicked and listned to on the child component.
        // 3. (Store pattern) Shared state, create a custom module called ThreadStore which is the single source of truth, both thread and replies components will reference it.
        // 4. use VueX - formalized design pattern
        // 5. use the $parent.locked on the replies comp, it will then take the locked state from thread.
        lockThread(){
            this.locked = true;
            window.events.$emit('lockThread');

            this.persist();
        },

        // persist(){
        //     axios.post('/locked-threads/{thread}')
        // }
    }
}
</script>