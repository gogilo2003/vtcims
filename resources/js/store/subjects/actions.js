import axios from "axios"

const token = localStorage.getItem('token')

export default {
    setSubject(context, payload) {
        context.commit('SET_SUBJECT', payload)
    },
    async fetchSubjects({ commit }) {
        let search = localStorage.getItem('search')
        return axios.get(`/api/eschool/subjects?api_token=${token}&search=${search}`).then(res => {
            commit('SET_SUBJECTS', res.data.data)
            return res
        })
    },

    async fetchSubject({ commit }, payload) {
        return axios.get(`/api/eschool/subjects/show/${payload}?api_token=${token}`).then(res => {
            commit('SET_SUBJECT', res.data)
            return res.data
        })
    },

    async storeSubject({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/subjects`, payload).then(res => {
            commit('ADD_SUBJECT', res.data.subject)
            return res
        })
    },

    async updateSubject({ commit }, payload) {
        payload.api_token = token
        return axios.patch(`/api/eschool/subjects`, payload).then(res => {
            commit('UPDATE_SUBJECT', res.data.subject)
            return res
        })
    },

    setFiltered(context, payload) {
        context.commit('SET_FILTERED', payload)
    }
}
