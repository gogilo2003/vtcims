export default {
    SET_TERMS: (state, terms) => {
        state.terms = terms
    },
    SET_TERM: (state, term) => {
        state.term = term
    },
    ADD_TERM(state, payload) {
        state.terms = [payload, ...state.terms]
    },
    UPDATE_TERM(state, payload) {
        state.terms = state.terms.map((item) => {
            if (payload.id === item.id) {
                return payload
            }
            return item
        })
    },
}