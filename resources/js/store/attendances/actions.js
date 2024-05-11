import axios from "axios"

const token = localStorage.getItem('token')

export default {
    async list({ commit }) {
        return axios.get(`/api/eschool/attendances?api_token=${token}`).then(response => {
            commit('SET_TERMS', response.data)
        }).catch(error => { })
    },
    async view({ commit }, payload) {
        return axios.get(`/api/eschool/attendances/view/${payload}/?api_token=${token}`).then(response => {
            commit('SET_TERM', response.data)
        })
    },
    async store({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/attendances/`, payload).then(response => {
            commit('SET_TERM', response.data.data.course)
        })
    },
    async update({ commit }, payload) {
        return axios.patch(`/api/eschool/attendances/view/`, { api_token: token, ...payload }).then(response => {
            commit('SET_TERM', response.data.data.course)
        })
    }
}
