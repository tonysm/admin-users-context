<template>
    <tr>
        <td>{{ user.name }}</td>
        <td>{{ user.is_admin ? 'Admin' : 'Member' }}</td>
        <td v-if="user.groups.length > 0">
            {{ user.groups.map(g => g.name).join(', ') }}
            <a href="" v-b-modal="`userToGroupsModal${user.id}`" @click.prevent="() => {}">Manage</a>
        </td>
        <td v-else>
            No groups.
            <a href="" v-b-modal="`userToGroupsModal${user.id}`" @click.prevent="() => {}">Manage</a>
        </td>

        <b-modal
            :id="`userToGroupsModal${user.id}`"
            title="Associate Group"
            ref="modal"
            @ok="handleOk"
            :ok-disabled="isSaving || !selectedGroups"
        >
            <p class="alert alert-info">
                Pick the groups you want to associate with user <strong>{{ user.name }}</strong> and press ok.
            </p>
            <div class="ml-4">
                <form @submit.stop.prevent="handleSubmit">
                    <div class="form-group form-check" v-for="group in groups" :key="group.id">
                        <input @change="toggleGroup(group)" type="checkbox" class="form-check-input" :id="`${user.id}groupField${group.id}`" :checked="selectedGroups.includes(group.id)">
                        <label class="form-check-label" :for="`${user.id}groupField${group.id}`">{{ group.name }}</label>
                    </div>
                </form>
            </div>
        </b-modal>
    </tr>
</template>

<script>
    import Vue from 'vue';
    import {mapState, mapActions} from 'vuex';
    import * as Constants from '../store/users/constants';

    export default {
        name: "UserRow",
        props: ['user', 'groups'],
        data () {
            return {
                selectedGroups: this.user.groups.map(g => g.id),
            };
        },
        watch: {
            user (newUser) {
                this.selectedGroups = newUser.groups.map(g => g.id) || [];
            },
        },
        computed: {
            ...mapState({
                isSaving: (state) => state.users.status === Constants.STATUS_SAVING,
            }),
        },
        methods: {
            ...mapActions({
                addUserToGroup: Constants.ADD_USER_TO_GROUP,
            }),
            handleOk(e) {
                // Prevent modal from closing
                e.preventDefault();

                this.handleSubmit();
            },
            handleSubmit() {
                this.addUserToGroup({
                        user: this.user,
                        groups: this.selectedGroups,
                    })
                    .then(() => {
                        this.$refs.modal.hide();
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    })
            },
            clearErrors(field) {
                Vue.delete(this.errors, field);
            },
            toggleGroup (group) {
                if (this.selectedGroups.includes(group.id)) {
                    this.selectedGroups = this.selectedGroups.filter(groupId => groupId !== group.id);
                } else {
                    this.selectedGroups.push(group.id);
                }
            }
        }
    }
</script>

<style scoped>

</style>