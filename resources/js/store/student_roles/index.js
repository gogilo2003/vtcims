import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    list: [],
    meta: {},
    student_role: {},
}
export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};
