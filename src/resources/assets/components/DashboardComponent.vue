<template>
    <div class="row row-stretch">
        <!-- MODAL -->
            <!--ADD NEW GROUP-->
            <div class="modal fade" id="modal-add-group" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-light">
                            <h5 class="modal-title">Add Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
            
                        <form id="form-add-user" v-on:submit.prevent="addGroup">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Acount Name</label> 
                                            <input type="text" name="name" v-model="name" class="form-control"> 

                                            <label>Account Email</label> 
                                            <input type="email" name="email" v-model="email" class="form-control"> 
                                        </div>                                         
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    Add
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--ADD NEW GROUP END-->
        <!-- END MODAL -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-info text-light">
                            <button type="button" class="btn btn-success" @click="showAddGroup()">
                                <span class="fa fa-plus"></span>
                                Add Account
                            </button>
                        </div>
                        <div class="card-body">
                            <h4>Accounts</h4>          
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Account email</th>
                                        <th>Creation Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!clients.length">
                                        <td class="text-center" colspan="4">
                                            <div class="mt-4">
                                                No Accounts found. 
                                                <a href="#" @click="showAddGroup()" style="text-decoration:none;">
                                                    <!-- <span class="fa fa-plus text-primary"></span> -->
                                                    <span class="text-primary"> Add one ?</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="client in clients" v-else>
                                        <td>
                                            <!-- <a :href="'/client/'+client.client_uuid">{{ client.name }}</a> -->
                                            <a :href="'/account/'+client.slug+'/'+client.client_uuid">{{ client.name }}</a>
                                        </td>
                                        <td>{{ client.email }}</td>
                                        <td>{{ client.created_at}}</td>
                                        <td>
                                            <a href="#" class="ml-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- <a href="#" @click.prevent="removeClientCredential(credential.id, credential.name)"> -->
                                            <a href="#" class="ml-2" @click.prevent="showRemoveGroup(client.id, client.name)">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <a :href="'/send-mail/account/'+client.slug+'/'+client.client_uuid" class="ml-2">
                                                <i class="fa fa-envelope"></i>
                                            </a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modals -->
        <!--Remove Client-->
        <div class="modal fade" id="modal-remove-group" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title">Remove <em>{{g_name}}</em></h5>
                    </div>
        
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Are you sure ?</h5>
                                <p><em>Removing this will delete all the email groups/subscribers registered on this account.</em></p>                                     
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-dismiss="modal" @click="removeGroup(g_id, g_name)">
                            Yes
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
export default {
    created() {
        this.getGroup();
    },
    data() {
        return {
            email: '',
            name: '',
            clients: [],

            g_id: null,
            g_name: null,
        }
    },
    methods: {
        addGroup: function() {
            axios.post(window.location.origin + '/management', {
                name:   this.name,
                email:  this.email
            }).then(response => {
                if(response.data.success) {
                    this.name = '';
                    this.email = '';
                    this.$toast.success(response.data.msg, 'Success', { position: 'topRight', zindex: 9999999 });
                    this.getGroup();
                    $('#modal-add-group').modal('hide');
                }
            }).catch(e => {
                console.log(e);
            });
        },
        getGroup: function() {
            axios.get(window.location.origin + '/management')
                .then(response => {
                    this.clients = response.data.clients;
                }).catch(e => {
                    console.log(e);
                });
        },
        removeGroup: function(client, name) {
            axios.delete(window.location.origin + '/management/' + client)
                .then(response => {
                    if(response.data.success) {
                        this.$toast.success(name + response.data.msg, 'Success', { position: 'topRight', zindex: 99999999 });
                        this.getGroup();
                    }
                }).catch(e => {
                    console.log(e);
                });
        },
        showAddGroup: function() {
            $('#modal-add-group').modal('show');
        },
        showRemoveGroup: function(id, name) {
            this.g_id = id;
            this.g_name = name;
            $('#modal-remove-group').modal('show');
        }
    }
}
</script>