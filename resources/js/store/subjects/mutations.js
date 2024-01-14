export default {
    SET_SUBJECT(state, payload) {
        state.subject = payload
    },
    SET_SUBJECTS(state, payload) {
        state.subjects = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    },
    ADD_SUBJECT(state, payload) {
        state.subjects = [payload, ...state.subjects]
    },
    UPDATE_SUBJECT(state, payload) {
        state.subjects = state.subjects.map((item) => {
            if (payload.id === item.id) {
                return payload
            }
            return item
        })
    },
}
