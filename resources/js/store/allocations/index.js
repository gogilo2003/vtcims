import actions from './actions';
import mutations from './mutations';

const state = {
    attendance: {},
    attendances: [],
};

export default {
    namespaced: true,
    state,
    actions,
    mutations,
};