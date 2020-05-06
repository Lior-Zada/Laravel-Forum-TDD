// Treat mixin the same way you would a Trait in Laravel.

export default {

    data(){
        return {
            items: []
        };
    },

    methods: {

        remove(index) {
            this.items.splice(index, 1);
            this.$emit("removed");
        },

        add(item) {
            this.items.push(item);
            this.$emit("added");
        }
    }
};
