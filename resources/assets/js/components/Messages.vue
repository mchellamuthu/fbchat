<template>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">Contacts</div>

        <div class="panel-body">
          <div class="list-group">

          <router-link  v-for="contact in contacts" :key="contact.key" :to="{ name: 'chat', params: { userId: contact.id}}" class="list-group-item">
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
        <div class="panel-body">

        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  data() {
    return {
      contacts: []
    }
  },
  created() {
    this.getContacts();
  },
  mounted() {
    console.log('Component mounted.')
  },
  methods: {
    getContacts() {
      axios.get('/users').then((response) => {
        this.contacts = response.data.data;
      }).catch((error) => {
        console.log(error);
      });
    }
  }
}
</script>
