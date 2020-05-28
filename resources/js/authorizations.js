let user = window.App.user;

let authorizations = {

    updateReply(reply) {
        return reply.user_id == user.id;
      },
    
    updateAvatar(userProfile){
        return userProfile.id === user.id;
    },
    
    markBestReply(reply){
        return reply.thread.user_id == user.id;
    },

    isAdmin(){
        return ['LiorZada'].includes(user.name);
    }
};

module.exports = authorizations;