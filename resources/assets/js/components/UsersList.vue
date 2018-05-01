<template>
    <div class="card card-default">
        <div class="card-header">
            Users ({{users.items.length}})
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
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import * as Constants from '../store/users/constants';

    export default {
        name: "UsersList",
        computed: {
            ...mapState({
                users: (state) => {
                    return state.users.users;
                },
                groups: (state) => state.users.groups,
                isLoading: (state) => state.users.status === Constants.STATUS_LOADING,
                isSaving: (state) => state.users.status === Constants.STATUS_SAVING,
            }),
        }
    }
</script>