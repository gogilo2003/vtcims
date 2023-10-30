import Vue from 'vue';
import Vuex from 'vuex';

// Store Modules
import students from './students';
import staff from './staff';
import fees from './fees';
import terms from './terms';
import courses from './courses';
import remarks from './remarks';
import vote_heads from './vote_heads';

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: false,
    modules: {
        students,
        staff,
        fees,
        terms,
        courses,
        remarks,
        vote_heads,
    },
});