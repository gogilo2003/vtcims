import axios from "axios"

const token = localStorage.getItem('token')

export default {
    async list({ commit }) {
        axios.get(`/api/eschool/grading/remarks?api_token=${token}`).then(response => {
            commit('SET_REMARKS', response.data.data)
        })
    },
    async view({ commit }, payload) {
        return axios.get(`/api/eschool/grading/remarks/${payload}/?api_token=${token}`).then(response => {
            commit('SET_REMARK', response.data)
        })
    },
    async store({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/grading/remarks`, payload).then(response => {
            commit('ADD_REMARK', response.data.remark)
            return response
        });
    },
    async update({ commit }, payload) {
        return axios.patch(`/api/eschool/grading/remarks`, { api_token: token, ...payload }).then(response => {
            commit('UPDATE_REMARK', response.data.fee, payload)
        })
    }
}