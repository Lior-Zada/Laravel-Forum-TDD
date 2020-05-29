let user = window.App.user;

let authorizations = {

    owns(model){
        return model.user_id === user.id;
    },
    
    markBestReply(reply){
        return reply.thread.user_id == user.id;
    },

    isAdmin(){
        return ['LiorZada'].includes(user.name);
    }
};

module.exports = authorizations;