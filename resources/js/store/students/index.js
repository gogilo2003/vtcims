import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    students: [],
    student: {},
    filtered: {}
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};
