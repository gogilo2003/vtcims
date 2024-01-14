import axios from "axios"

const token = localStorage.getItem('token')
export default {
    fetchSponsors({ commit }) {
        axios.get(`/api/eschool/sponsors?api_token=${token}`).then(res => {
            commit('SET_SPONSORS', res.data.data)
            commit('SET_META', res.data.meta)
            return res
        })
    }
}