<template>
    <div class="row row-stretch">
        <!-- MODAL -->
        <!--EDIT SUBSCRIBER-->
            <div class="modal fade" id="modal-edit-subscriber" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-light">
                            <h5 class="modal-title">Edit Subscriber</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
            
                        <form id="form-add-user" v-on:submit.prevent="updateSubscriber">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Firstname</label> 
                                            <input type="text" name="efname" v-model="efname" class="form-control">
                                            <label>Lastname</label> 
                                            <input type="text" name="elname" v-model="elname" class="form-control"> 
                                            <label>Email</label> 
                                            <input type="text" name="eemail" v-model="eemail" class="form-control"> 
                                        </div>                                         
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--EDIT SUBSCRIBER END-->
        <!-- END MODAL -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-info text-light">
                            <h2>{{ client.name }} Subscribers</h2>
                        </div>
                        <div class="card-body">
                            <a type="button" class="btn btn-success mb-2" :href="'/import-subscriber/'+account+'/'+account_id+'/'+group+''">
                                <span class="fa fa-plus"></span>
                                Import
                            </a>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!subscribers.length">
                                        <td class="text-center" colspan="3">
                                            <div class="mt-4">
                                                Empty. No subscriber(s) found.
                                                    <a :href="'/import-subscriber/'+account+'/'+account_id+'/'+group+''" style="text-decoration:none;">
                                                    <span class="text-primary"> Import ?</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr v-for="subscriber in subscribers" v-else>
                                        <td>{{ subscriber.lastname }}</td>
                                        <td>{{ subscriber.firstname }}</td>
                                        <td><a :href="'/'+account+'/'+group+'/api/stats/email/'+subscriber.email" style="text-decoration: none;">{{ subscriber.email }}</a></td>
                                        <td>
                                            <a href="" class="ml-2" @click.prevent="showEditSubscriber(subscriber.id, subscriber.firstname)">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- <a href="#" @click.prevent="removeClientCredential(credential.id, credential.name)"> -->
                                            <a href="" class="ml-2" @click.prevent="showRemoveSubscriber(subscriber.id, subscriber.firstname)">
                                                <i class="fa fa-trash"></i>
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
                                <p><em>Removing this will delete all the email subscribers registered on this group.</em></p>                                     
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-dismiss="modal" @click="removeSubscriber(g_id, g_name)">
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
        this.getSubscriber();
    },
    data() {
        return {
            name: '',
            account: this.accountslug,
            account_id: this.accountuuid,
            group: this.groupslug,
            client: [],
            subscribers: [],

            efname: '',
            elname: '',
            eemail: '',
            esubscriber: [],

            g_id: null,
            g_name: null,
        }
    },
    methods: {
        getSubscriber: function() {
            axios.get(window.location.origin + '/group-subscribers/'+this.account_id+'/'+this.group)
                .then(response => {
                    this.subscribers = response.data.subscribers;
                    this.client = response.data.client;
                }).catch(e => {
                    console.log(e);
                });
        },
        addSubscriber: function() {

        },
        updateSubscriber: function() {
            axios.put(window.location.origin + '/update-subscriber/'+this.g_id, {
                firstname: this.efname,
                lastname: this.elname,
                email: this.eemail,
            }).then(response => {
                if(response.data.success) {
                    this.$toast.success(response.data.msg, 'Success', { position: 'topRight', zindex: 9999999 });
                    this.getSubscriber();
                    $('#modal-edit-subscriber').modal('hide');
                }
            }).catch(e => {
                console.log(e);
            });
        },
        removeSubscriber: function(id, name) {
            axios.delete(window.location.origin + '/remove-subscriber/' + id)
                .then(response => {
                    if(response.data.success) {
                        this.$toast.success(name + response.data.msg, 'Success', { position: 'topRight', zindex: 99999999 });
                        this.getSubscriber();
                    }
                }).catch(e => {
                    console.log(e);
                });
        },
        // showAddGroup: function() {
        //     $('#modal-add-group').modal('show');
        // },
        showEditSubscriber: function(id, name) {
            axios.get(window.location.origin + '/get-subscriber', {
                params: {
                    id: id,
                }
            }).then(response => {
                this.esubscriber = response.data.subscriber;
                this.efname = this.esubscriber.firstname;
                this.elname = this.esubscriber.lastname;
                this.eemail = this.esubscriber.email;
                this.g_id = this.esubscriber.id;
                this.g_name = this.esubscriber.name;
                $("#modal-edit-subscriber").modal("show");
            }).catch(e => {
                console.log(e);
            });
        },
        showRemoveSubscriber: function(id, name) {
            this.g_id = id;
            this.g_name = name;
            $('#modal-remove-group').modal('show');
        }
    },
    props: ['accountslug', 'accountuuid', 'groupslug']
}
</script>