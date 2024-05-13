import axios from "axios"

const token = localStorage.getItem('token')

export default {
    setStudentAction(context, payload) {
        context.commit('SET_STUDENT', payload)
    },
    async fetchStudents({ commit }) {
        let search = localStorage.getItem('search')
        return axios.get(`/api/eschool/students?api_token=${token}&search=${search}`).then(res => {
            commit('SET_STUDENTS', res.data.data)
            commit('SET_META', res.data.meta)
            return res
        })
    },

    async fetchStudent({ commit }, payload) {
        return axios.get(`/api/eschool/students/show/${payload}?api_token=${token}`).then(res => {
            commit('SET_STUDENT', res.data)
            return res.data
        })
    },

    async storeStudent({ commit }, payload) {
        payload.api_token = token
        return axios.post(`/api/eschool/students`, payload).then(res => {
            commit('ADD_STUDENT', res.data.student)
            return res
        })
    },

    async updateStudent({ commit }, payload) {
        payload.api_token = token
        return axios.patch(`/api/eschool/students`, payload).then(res => {
            commit('UPDATE_STUDENT', res.data.student)
            return res
        })
    },

    setFiltered(context, payload) {
        context.commit('SET_FILTERED', payload)
    }
}
