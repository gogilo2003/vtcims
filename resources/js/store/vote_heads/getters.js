import axios from 'axios'
export default {
    getVoteHead(state) {
        return state.vote_head
    },
    getVoteHeads(state) {
        return state.vote_heads
    },
    getFiltered(state) {
        return state.filtered
    }
}