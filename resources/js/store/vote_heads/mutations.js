
export default {
    SET_STUDENT(state, payload) {
        state.vote_head = payload
    },
    SET_VOTE_HEADS(state, payload) {
        state.vote_heads = payload
    },
    FETCH_VOTE_HEADS(state, payload) {
        state.vote_heads = payload
    },
    SET_FILTERED(state, payload) {
        state.filtered = payload
    }
}
