import axios from 'axios';

const token = localStorage.getItem('token')

export default class VoteHeadService {
    async list() {
        return await axios.get(`/api/eschool/fees/vote_heads?api_token=${token}`)
    }

    async show(id) {
        return await axios.get(`/api/eschool/fees/vote_heads/${id}?api_token=${token}`)
    }

    async delete(id) {
        return await axios.delete(`/api/eschool/fees/vote_heads/${id}?api_token=${token}`)
    }

    async create(fee, vote_head, share) {
        return await axios.post(`/api/eschool/fees/vote_heads?api_token=${token}`, { fee, vote_head, share })
    }

    async update(id, fee, vote_head, share) {
        return await axios.patch(`/api/eschool/fees/vote_heads/${id}?api_token=${token}`, { fee, vote_head, share })
    }
}