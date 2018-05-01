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
    },
    [Constants.ADD_USER_TO_GROUP] ({commit, dispatch, state}, {user, groups}) {
        commit(Constants.SAVING_ASSOCIATION);

        const userGroups = user.groups.map(group => group.id);
        const newGroups = groups.filter(groupId => !userGroups.includes(groupId));
        const removedGroups = userGroups.filter(groupId => !groups.includes(groupId));

        const newGroupsPromises = newGroups.map((groupId) => {
            return axios.post(`/api/groups/${groupId}/users`, {
                user_id: user.id,
            });
        });

        const removeGroupsPromises = removedGroups.map((groupId) => {
            return axios.delete(`/api/groups/${groupId}/users/${user.id}`);
        });

        return Promise.all(newGroupsPromises.concat(removeGroupsPromises))
            .then(() => {
                    dispatch({
                        type: Constants.LOAD_USERS,
                        page: state.users.page,
                    })
                    .then(() => {
                        commit(Constants.ASSOCIATIONS_SAVED)
                    });
            });
    }
}