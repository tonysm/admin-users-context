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
                console.log(data);
            });
    },
}