<template>
    <div class="row row-stretch">
        <!-- MODAL -->
            <!--ADD NEW GROUP-->
            <div class="modal fade" id="modal-add-group" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-light">
                            <h5 class="modal-title">Add Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
            
                        <form id="form-add-user" v-on:submit.prevent="addGroup">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Group Name</label> 
                                            <input type="text" name="name" v-model="name" class="form-control"> 
                                            <input type="hidden" name="slug" id="slug" v-model="account">
                                            <input type="hidden" name="client_id" id="client_id" v-model="client_id">
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
            <!--EDIT GROUP-->
            <div class="modal fade" id="modal-edit-group" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-light">
                            <h5 class="modal-title">Edit Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
            
                        <form id="form-add-user" v-on:submit.prevent="updateGroup">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Group Name</label> 
                                            <input type="text" name="ename" v-model="ename" class="form-control"> 
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
            <!--EDIT GROUP END-->
        <!-- END MODAL -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-info text-light">
                            <button type="button" class="btn btn-success" @click="showAddGroup()">
                                <span class="fa fa-plus"></span>
                                Add Group
                            </button>
                        </div>
                        <div class="card-body">
                            <h4>Groups</h4>          
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Creation Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="!groups.length">
                                        <td class="text-center" colspan="4">
                                            <div class="mt-4">
                                                No Groups found. 
                                                <a href="#" @click="showAddGroup()" style="text-decoration:none;">
                                                    <!-- <span class="fa fa-plus text-primary"></span> -->
                                                    <span class="text-primary"> Add one ?</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="group in groups" v-else>
                                        <td>
                                            <a :href="'/group/'+group.client_id+'/'+group.slug">{{ group.name }}</a>
                                        </td>
                                        <td>{{ group.created_at}}</td>
                                        <td>
                                            <a href="#" class="ml-2" @click.prevent="showEditGroup(group.id, group.name)">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- <a href="#" @click.prevent="removeClientCredential(credential.id, credential.name)"> -->
                                            <a href="#" class="ml-2" @click.prevent="showRemoveGroup(group.id, group.name)">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <a :href="'/send-mail/group/'+account+'/'+group.client_id+'/'+group.slug" class="ml-2">
                                                <i class="fa fa-envelope"></i>
                                            </a>
                                            <a :href="'/'+account+'/'+group.slug+'/api/stats/batch/'+group.slug" class="ml-2">
                                                <i class="fa fa-chart-bar"></i>
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
            name: '',
            account: this.slug,
            client_id: this.client,
            groups: [],

            ename: '',
            egroup: [],

            g_id: null,
            g_name: null,
        }
    },
    methods: {
        addGroup: function() {
            axios.post(window.location.origin + '/group', {
                name:   this.name,
                slug:   this.account,
                client_id: this.client_id,
            }).then(response => {
                if(response.data.success) {
                    this.name = '';
                    this.$toast.success(response.data.msg, 'Success', { position: 'topRight', zindex: 9999999 });
                    this.getGroup();
                    $('#modal-add-group').modal('hide');
                }
            }).catch(e => {
                console.log(e);
            });
        },
        updateGroup: function() {
            axios.put(window.location.origin + '/update-group/'+this.g_id, {
                name: this.ename,
            }).then(response => {
                    if(response.data.success) {
                        this.$toast.success(response.data.msg, 'Success', { position: 'topRight', zindex: 9999999 });
                        this.getGroup();
                        $('#modal-edit-group').modal('hide');
                    }
            }).catch(e => {
                console.log(e);
            });
        },
        getGroup: function() {
            axios.get(window.location.origin + '/account-group/'+this.client)
                .then(response => {
                    this.groups = response.data.groups;
                }).catch(e => {
                    console.log(e);
                });
        },
        removeGroup: function(group, name) {
            axios.delete(window.location.origin + '/group/' + group)
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
        showEditGroup: function(id, name) {
            axios.get(window.location.origin + '/get-group', {
                params: {
                    id: id,
                }
            }).then(response => {
                this.egroup = response.data.group;
                this.ename = this.egroup.name;
                this.g_id = this.egroup.id;
                this.g_name = this.egroup.name;
                $("#modal-edit-group").modal("show");
            }).catch(e => {
                console.log(e);
            });
        },
        showRemoveGroup: function(id, name) {
            this.g_id = id;
            this.g_name = name;
            $('#modal-remove-group').modal('show');
        }
    },
    props: ['slug', 'client', 'data']
}
</script>