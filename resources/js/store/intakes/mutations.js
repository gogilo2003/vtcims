export default {
    SET_INTAKES(state, payload) {
        state.list = payload
    },
    SET_INTAKE(state, payload) {
        state.intake = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    }
}