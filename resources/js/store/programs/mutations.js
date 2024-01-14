export default {
    SET_PROGRAMS(state, payload) {
        state.list = payload
    },
    SET_PROGRAM(state, payload) {
        state.program = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    }
}