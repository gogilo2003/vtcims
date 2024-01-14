export default {
    SET_SPONSORS(state, payload) {
        state.list = payload
    },
    SET_SPONSOR(state, payload) {
        state.sponsor = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    }
}