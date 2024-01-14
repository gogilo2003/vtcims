import axios from "axios"

const token = localStorage.getItem('token')

export default {
    setAllocation(context, payload) {
        context.commit('SET_ALLOCATION', payload)
    },
    async fetchAllocations({ commit }) {
        let search = localStorage.getItem('search')
        return axios.get(`/api/eschool/allocations?api_token=${token}&search=${search}`).then(res => {
            commit('SET_ALLOCATIONS', res.data.data)
            return res
        })
    },

    async fetchAllocation({ commit }, payload) {
        return axios.get(`/api/eschool/allocations/show/${payload}?api_token=${token}`).then(res => {
            commit('SET_ALLOCATION', res.data)
            return res.data
        })
    },

    async storeAllocation({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/allocations`, payload).then(res => {
            commit('ADD_ALLOCATION', res.data.student)
            return res
        })
    },

    async updateAllocation({ commit }, payload) {
        payload.api_token = token
        return axios.patch(`/api/eschool/allocations`, payload).then(res => {
            commit('UPDATE_ALLOCATION', res.data.student)
            return res
        })
    },

}
