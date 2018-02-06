<template>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">Contacts</div>

        <div class="panel-body">
          <div class="list-group">
            <router-link  v-for="contact in contacts" :key="contact.name" :to="{ name: 'chat', params: { userId: contact.id}}" class="list-group-item" :id="contact.id">
               <h4 class="list-group-item-heading">{{contact.name}}</h4>
               <p class="list-group-item-text">{{contact.lastmsg}}</p>
              </router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">Messages</div>
        <div class="panel-body" style="height:500px; overflow-y:auto" id="messages">
          <div class="col-md-12" id="msg_row" v-for="message in messages" :key="message.id">
            <div class="msg_me" v-if="message.user_to==userId">
              {{message.msg}}
            </div>
            <div class="msg_to" v-if="message.user_from==userId">
              {{message.msg}}
            </div>
          </div>
          <div class="col-md-12 chat-composer">

          </div>
        </div>
        <div class="panel-footer">
          <form @submit.prevent="SendMessage">
            <div class="input-group">
        <input type="text" class="form-control" placeholder="Type your message..." v-model="sendMsgText">
        <span class="input-group-btn">
          <button class="btn btn-info" type="submit">Send</button>
        </span>
      </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: ['userId'],
  data() {
    return {
      contacts: [],
      messages: [],
      sendMsgText : ''
    }
  },
  created() {
  this.getContacts();
    this.getMessages();
    this.scrollToBottom();
    Echo.channel('chat-message'+window.userid)
    .listen('SendMessage', (e) => {
      document.getElementById('chataudio').play();
        this.messages.push(e.message);
        console.log(e.message);
        for (var i = 0; i < this.contacts.length; i++) {
          if (this.contacts[i].id==e.message.user_from) {
            this.contacts[i].lastmsg = e.message.msg;
            $('#'+e.message.user_from).addClass('active');
          }
          else{
            this.contacts.push({
              id : e.message.user_from,
              name : e.message.name,
              lastmsg : e.message.msg,
              msg : [],
              type :'Receive'
            });
            $('#'+e.message.user_from).addClass('active');
          }
        }
          this.scrollToBottom();
          this.markAsRead(e.message.id);

    });
  },
  mounted() {
    console.log('Component mounted.')
  this.scrollToBottom();
  },
  watch: {
   '$route.params.userId': function (userId) {
     this.getMessages();
     this.scrollToBottom();
   }
 },
  methods: {
    getContacts() {
      axios.get('/users').then((response) => {
        this.contacts = response.data.data;
      }).catch((error) => {
        console.log(error);
      });
    },
    getMessages(){
      axios.get('/getMessages/'+this.userId).then((response) => {
        this.messages = response.data.data;
        this.scrollToBottom();
      }).catch((error) => {
        console.log(error);
      });
    },
    SendMessage(){
      axios.post('/sendMessage/'+this.userId,{
          message : this.sendMsgText
      }).then((response) => {
        this.messages.push(response.data.data);
        this.sendMsgText = '';
        this.scrollToBottom();
      }).catch((error) => {
        console.log(error);
      });
    },
   scrollToBottom() {
     var height = 0;
     $('#messages #msg_row').each(function(i, value){
         height += parseInt($(this).height());
     });
     height += '';
     $('#messages').animate({scrollTop: height});
   },
   markAsRead(msgid){
     axios.post('/markAsRead/'+msgid,{
         message : true
     }).then((response) => {
       // console.log(response.data.data);
     }).catch((error) => {
       console.log(error);
     });
   },


  }
}
</script>
<style>
    .msg_me{
      background: #f3f3f3;
      border: solid 1px #ccc;
      padding: 5px 10px;
      display: block;
      float: right;
      border-radius: 5px;
    }
    .msg_to{
      background: #6464c1;
      color: #fff;
      padding: 5px 10px;
      border: solid 1px #ccc;
      display: block;
      float: left;
      border-radius: 5px;
    }
    .chat-composer{

    }
</style>
