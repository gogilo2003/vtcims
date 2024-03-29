import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    subjects: [],
    subject: {},
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};
