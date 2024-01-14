import { createStore } from 'vuex';

// Store Modules
import students from './students';
import staff from './staff';
import fees from './fees';
import terms from './terms';
import courses from './courses';
import remarks from './remarks';
import vote_heads from './vote_heads';
import intakes from './intakes';
import sponsors from './sponsors';
import programs from './programs';
import student_roles from './student_roles';
import attendances from './attendances';
import allocations from './allocations';
import subjects from './subjects';

export default createStore({
    modules: {
        students,
        staff,
        fees,
        terms,
        courses,
        remarks,
        vote_heads,
        intakes,
        sponsors,
        programs,
        student_roles,
        attendances,
        allocations,
        subjects,
    }
})
// export default new Vuex.Store({
//     namespaced: false,
//     modules: {
//         students,
//         staff,
//         fees,
//         terms,
//         courses,
//         remarks,
//         vote_heads,
//     },
// });
