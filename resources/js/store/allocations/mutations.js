export default {
    SET_ALLOCATIONS: (state, allocations) => {
        state.allocations = allocations
    },
    SET_ALLOCATION: (state, allocation) => {
        state.allocation = allocation
    },
    ADD_ALLOCATION(state, payload) {
        state.allocations = [payload, ...state.allocations]
    },
    UPDATE_ALLOCATION(state, payload) {
        state.allocations = state.allocations.map((item) => {
            if (payload.id === item.id) {
                return payload
            }
            return item
        })
    },
}