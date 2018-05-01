<template>
    <div class="card card-default">
        <div class="card-header d-flex justify-content-between align-items-center">
            Users ({{users.items.length}})

            <button class="btn btn-default btn-sm" v-b-modal.usersModal>
                New User
            </button>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Groups</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users.items" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td>{{ user.is_admin ? 'Admin' : 'Member' }}</td>
                    <td v-if="user.groups.length > 0">{{ user.groups.map(g => g.name).join(', ') }}</td>
                    <td v-else>No groups.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <b-modal
                id="usersModal"
                title="New User"
                ref="modal"
                @ok="handleOk"
                :ok-disabled="isSaving"
        >
            <form @submit.stop.prevent="handleSubmit">
                <div class="form-group">
                    <label for="userNameInput">Name</label>
                    <input
                            @input="clearErrors('name')"
                            type="text"
                            v-model="newUser.name"
                            class="form-control"
                            id="userNameInput"
                            placeholder="Enter user name"
                            :class="{'is-invalid': errors.name }"
                    />
                    <small id="nameHelp" class="invalid-feedback" v-if="errors.name">{{ errors.name[0] }}</small>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import Vue from 'vue';
    import {mapState, mapActions} from 'vuex';
    import * as Constants from '../store/users/constants';

    export default {
        name: "UsersList",
        data () {
            return {
                newUser: {
                    name: '',
                },
                errors: {},
            }
        },
        computed: {
            ...mapState({
                users: (state) => {
                    return state.users.users;
                },
                groups: (state) => state.users.groups,
                isLoading: (state) => state.users.status === Constants.STATUS_LOADING,
                isSaving: (state) => state.users.status === Constants.STATUS_SAVING,
            }),
        },
        methods: {
            ...mapActions({
                createUser: Constants.CREATE_USER,
            }),
            handleOk(e) {
                // Prevent modal from closing
                e.preventDefault();

                this.handleSubmit();
            },
            handleSubmit() {
                this.createUser({name: this.newUser.name})
                    .then(() => {
                        this.newUser.name = '';
                        this.$refs.modal.hide();
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    })
            },
            clearErrors(field) {
                Vue.delete(this.errors, field);
            }
        }
    }
</script>