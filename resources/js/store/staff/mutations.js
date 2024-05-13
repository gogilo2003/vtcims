export default {
    SET_STAFF_MEMBER(state, payload) {
        state.member = payload
    },
    SET_STAFF_MEMBERS(state, payload) {
        state.members = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    },
    ADD_STAFF_MEMBER(state, payload) {
        state.members = [payload, ...state.members]
    },
    UPDATE_STAFF_MEMBER(state, payload) {
        state.members = state.members.map((item) => {
            if (payload.id === item.id) {
                return payload
            }
            return item
        })
    },
}
