export default {
    SET_STUDENT_ROLES(state, payload) {
        state.list = payload
    },
    SET_STUDENT_ROLE(state, payload) {
        state.student_role = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    }
}