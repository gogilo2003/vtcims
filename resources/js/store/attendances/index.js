import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    attendance: {},
    attendances: [],
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};