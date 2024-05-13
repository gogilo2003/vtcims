import axios from "axios"

const token = localStorage.getItem('token')

export default {
    setStaffMember(context, payload) {
        context.commit('SET_STAFF_MEMBER', payload)
    },
    async fetchStaffMembers({ commit }) {
        let search = localStorage.getItem('search')
        return axios.get(`/api/eschool/staff_members?api_token=${token}&search=${search}`).then(res => {
            commit('SET_STAFF_MEMBERS', res.data.data)
            return res
        })
    },

    async fetchStaffMember({ commit }, payload) {
        return axios.get(`/api/eschool/staff_members/show/${payload}?api_token=${token}`).then(res => {
            commit('SET_STAFF_MEMBER', res.data)
            return res.data
        })
    },

    async storeStaffMember({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/staff_members`, payload).then(res => {
            commit('ADD_STAFF_MEMBER', res.data.student)
            return res
        })
    },

    async updateStaffMember({ commit }, payload) {
        payload.api_token = token
        return axios.patch(`/api/eschool/staff_members`, payload).then(res => {
            commit('UPDATE_STAFF_MEMBER', res.data.student)
            return res
        })
    },

}
