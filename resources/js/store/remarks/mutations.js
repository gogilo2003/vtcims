export default {
    SET_REMARKS: (state, remarks) => {
        state.list = remarks
    },
    ADD_REMARK: (state, remark) => {
        state.list.unshift(remark)
    },
    UPDATE_REMARK: (state, remark) => {
        let index = state.list.findIndex(item => remark.id == item.id)
        state.list[index] = remark
    },
    DELETE_REMARK: (state, remark) => {
        let index = state.list.findIndex(item => remark.id == item.id)
        state.list.splice(index, 1)
    }
}