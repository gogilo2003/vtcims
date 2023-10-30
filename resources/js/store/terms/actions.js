import axios from "axios"

const token = localStorage.getItem('token')

export default {
    async actionList({ commit }) {
        return axios.get(`/api/eschool/terms?api_token=${token}`).then(response => {
            commit('SET_TERMS', response.data)
        }).catch(error => { console.log(error) })
    },
    async actionView({ commit }, payload) {
        return axios.get(`/api/eschool/terms/view/${payload}/?api_token=${token}`).then(response => {
            commit('SET_TERM', response.data)
        })
    },
    async actionStore({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/terms/`, payload).then(response => {
            commit('SET_TERM', response.data.data.term)
        })
    },
    async actionUpdate({ commit }, payload) {
        return axios.patch(`/api/eschool/terms/view/`, { api_token: token, ...payload }).then(response => {
            commit('SET_TERM', response.data.data.term)
        })
    }
}