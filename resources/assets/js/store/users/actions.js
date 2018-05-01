import * as Constants from './constants';

export default {
    [Constants.BOOT_USERS_MODULE] ({dispatch}) {
        const usersPromise = dispatch(Constants.LOAD_USERS);
        const groupsPromise = dispatch(Constants.LOAD_GROUPS);

        return Promise.all([usersPromise, groupsPromise]);
    },
    [Constants.LOAD_USERS] ({commit}) {
        return axios.get('/api/users')
            .then(({data}) => {
                console.log(data);
            });
    },
    [Constants.LOAD_GROUPS] ({commit}) {
        return axios.get('/api/groups')
            .then(({data}) => {
                console.log(data);
            });
    },
}