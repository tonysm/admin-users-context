import * as Constants from './constants';

export default {
    [Constants.BOOT_USERS_MODULE] ({commit, dispatch}) {
        const usersPromise = dispatch(Constants.LOAD_USERS, {page: 1});
        const groupsPromise = dispatch(Constants.LOAD_GROUPS);

        return Promise.all([usersPromise, groupsPromise])
            .then(() => {
                commit({
                    type: Constants.USERS_MODULE_BOOTED,
                });
            });
    },
    [Constants.LOAD_USERS] ({commit}, { page }) {
        return axios.get(`/api/users?page=${page}`)
            .then(({data}) => {
                commit({
                    type: Constants.USERS_LOADED,
                    users: data.data,
                    page,
                    last_page: data.last_page,
                    per_page: data.per_page,
                    total: data.total,
                });
            });
    },
    [Constants.LOAD_GROUPS] ({commit}) {
        return axios.get('/api/groups')
            .then(({data}) => {
                commit({
                    type: Constants.GROUPS_LOADED,
                    groups: data.data,
                });
            });
    },
    [Constants.CREATE_GROUP] ({commit}, {name}) {
        return axios
            .post('/api/groups', {
                name,
            })
            .then(({data}) => {
                commit({
                    type: Constants.GROUP_CREATED,
                    group: data.data,
                });
            });
    },
    [Constants.CREATE_USER] ({dispatch}, {name}) {
        return axios
            .post('/api/users', {
                name,
            })
            .then(({data}) => {
                dispatch({
                    type: Constants.LOAD_USERS,
                    page: 1,
                });
            });
    }
}