<template>
  <li class="nav-item dropdown" v-show="notifications.length">
    <a
      id="navbarDropdown"
      class="nav-link dropdown-toggle"
      href="#"
      role="button"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
      v-pre
    >
      <i class="fas fa-envelope"></i>
      <span class="caret"></span>
    </a>

    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <li v-for="(notification, index) in notifications" :key="notification.id">
            <a class="dropdown-item"  :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification, index)"></a>
        </li>
    </ul>
  </li>
</template>

<script>
export default {
    data(){
        return {
            notifications: false
        };
    },

    created(){
        axios.get('/profiles/' + window.App.user.name + '/notifications').then(({data}) => this.notifications = data);
    },

    methods: {
        markAsRead(notification, index){
            axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`);
            this.notifications.splice(index);
        }
    }
};
</script>

<style>
</style>