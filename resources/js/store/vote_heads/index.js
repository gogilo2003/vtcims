import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
    vote_head: {},
    vote_heads: [],
};

export default {
    namespaced: true,
    state,
    actions,
    getters,
    mutations,
};