import axios from "axios"

const token = localStorage.getItem('token')
export default {
    fetchStudentRoles({ commit }) {
        axios.get(`/api/eschool/student_roles?api_token=${token}`).then(res => {
            commit('SET_STUDENT_ROLES', res.data.data)
            commit('SET_META', res.data.meta)
            return res
        })
    }
}