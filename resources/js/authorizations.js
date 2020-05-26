let user = window.App.user;

let authorizations = {

    updateReply(reply) {
        return reply.user_id == user.id;
      },
    
    updateAvatar(userProfile){
        return userProfile.id === user.id;
    },
    
    markBestReply(reply){
        return reply.user_id == user.id;
    }
};

module.exports = authorizations;