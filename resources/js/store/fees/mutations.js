export default {
    SET_FEES: (state, fees) => {
        state.fees = fees
    },
    ADD_FEE: (state, fee) => {
        state.fees.unshift(fee)
    },
    UPDATE_FEE: (state, fee) => {
        let index = state.fees.findIndex(item => fee.id == item.id)
        state.fees[index] = fee
    },
    DELETE_FEE: (state, fee) => {
        let index = state.fees.findIndex(item => fee.id == item.id)
        state.fees.splice(index, 1)
    }
}