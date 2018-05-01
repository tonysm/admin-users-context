import actions from './actions';
import mutations from './mutations';
import { STATUS_LOADING } from './constants';

export default {
    state: {
        status: STATUS_LOADING,
        users: {
            items: [],
            page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
        },
        groups: [],
    },
    actions,
    mutations,
}