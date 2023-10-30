<template>
    <div class="card">
        {{ vote_heads }}
    </div>
</template>
<script>
export default {
    data() {
        return {
            msg: "Fee Vote Heads",
        };
    },
    computed: {
        vote_heads() {
            return this.$store.state.vote_heads.vote_heads
        }
    },
    mounted() {
        this.$store.dispatch('vote_heads/fetchVoteHeads')
    }
};
</script>