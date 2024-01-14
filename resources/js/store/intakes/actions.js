import axios from "axios"

const token = localStorage.getItem('token')
export default {
    fetchIntakes({ commit }, payload = 0) {
        axios.get(`/api/eschool/intakes?api_token=${token}&active=${payload}`).then(res => {
            commit('SET_INTAKES', res.data.data)
            commit('SET_META', res.data.meta)
            return res
        })
    }
}