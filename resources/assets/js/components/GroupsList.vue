<template>
    <div class="card card-default">
        <div class="card-header">
            Groups ({{groups.length}})
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="group in groups" :key="group.id">
                    <td>{{ group.name }}</td>
                </tr>
                <tr v-if="groups.length === 0">
                    <td>No groups.</td>
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
        name: "GroupsList",
        computed: {
            ...mapState({
                groups: (state) => state.users.groups,
                isLoading: (state) => state.users.status === Constants.STATUS_LOADING,
                isSaving: (state) => state.users.status === Constants.STATUS_SAVING,
            }),
        }
    }
</script>