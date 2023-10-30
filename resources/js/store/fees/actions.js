import axios from "axios"

const token = localStorage.getItem('token')

export default {
    async actionList({ commit }) {
        axios.get(`/api/eschool/fees?api_token=${token}`).then(response => {
            commit('SET_FEES', response.data)
        })
    },
    async actionView({ commit }, payload) {
        return axios.get(`/api/eschool/fees/view/${payload}/?api_token=${token}`).then(response => {
            commit('SET_FEE', response.data)
        })
    },
    async actionStore({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/fees`, payload).then(response => {
            commit('ADD_FEE', response.data.fee)
            return response
        });
    },
    async actionUpdate({ commit }, payload) {
        return axios.patch(`/api/eschool/fees/view/`, { api_token: token, ...payload }).then(response => {
            commit('UPDATE_FEE', response.data.fee, payload)
        })
    }
}