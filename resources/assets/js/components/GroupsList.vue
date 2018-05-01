<template>
    <div class="card card-default" >
        <div class="card-header d-flex justify-content-between align-items-center">
            Groups ({{groups.length}})

            <button class="btn btn-default btn-sm" v-b-modal.groupsModal>
                New Group
            </button>
        </div>

        <div class="card-body">
            <table class="table" v-if="groups.length">
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="group in groups" :key="group.id">
                        <td>{{ group.name }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-else class="text-center">
                <p>No groups yet.</p>

                <button class="btn btn-info btn-lg" v-b-modal.groupsModal>
                    New Group
                </button>
            </div>
        </div>
        <b-modal
            id="groupsModal"
            title="New Group"
            ref="modal"
            @ok="handleOk"
            :ok-disabled="isSaving"
        >
            <form @submit.stop.prevent="handleSubmit">
                <div class="form-group">
                    <label for="groupNameInput">Group Name</label>
                    <input
                        @input="clearErrors('name')"
                        type="text"
                        v-model="newGroup.name"
                        class="form-control"
                        id="groupNameInput"
                        placeholder="Enter group name"
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
        name: "GroupsList",
        data () {
            return {
                newGroup: {
                    name: '',
                },
                errors: {},
            };
        },
        computed: {
            ...mapState({
                groups: (state) => state.users.groups,
                isLoading: (state) => state.users.status === Constants.STATUS_LOADING,
                isSaving: (state) => state.users.status === Constants.STATUS_SAVING,
            }),
        },
        methods: {
            ...mapActions({
                createGroup: Constants.CREATE_GROUP,
            }),
            handleOk(e) {
                // Prevent modal from closing
                e.preventDefault();

                this.handleSubmit();
            },
            handleSubmit() {
                this.createGroup({name: this.newGroup.name})
                    .then(() => {
                        this.newGroup.name = '';
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