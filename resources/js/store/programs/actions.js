import axios from "axios"

const token = localStorage.getItem('token')
export default {
    fetchPrograms({ commit }) {
        axios.get(`/api/eschool/programs?api_token=${token}`).then(res => {
            commit('SET_PROGRAMS', res.data.data)
            commit('SET_META', res.data.meta)
            return res
        })
    }
}