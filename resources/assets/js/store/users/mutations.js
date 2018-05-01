import * as Constants from './constants';

export default {
    [Constants.USERS_LOADED] (state, {users, page, last_page, per_page, total}) {
        state.users.items = users;
        state.users.page = page;
        state.users.last_page = last_page;
        state.users.per_page = per_page;
        state.users.total = total;
    },
    [Constants.GROUPS_LOADED] (state, {groups}) {
        state.groups = groups;
    },
    [Constants.USERS_MODULE_BOOTED] (state) {
        state.status = Constants.STATUS_DONE;
    },
}