import VoteHeadService from "./services";

const service = new VoteHeadService();

export default {
    setFiltered(context, payload) {
        context.commit('SET_FILTERED', payload)
    },
    fetchVoteHeads({ commit }) {
        service.list().then(res => {
            commit('SET_VOTE_HEADS', res.data.data)
        })
    },
    fetchVoteHead({ commit }, payload) {
        service.show(payload.id).then(res => {
            commit('SET_VOTE_HEADS', res.data.data)
        })
    }
}
