<template>
    <div class="card">
        <div class="card-header">
            <button
                type="button"
                class="btn btn-primary btn-sm rounded-pill"
                data-toggle="modal"
                data-target="#modelId"
            >
                Create New Fee
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>TERM</th>
                    <th>COURSE</th>
                    <th>AMOUNT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(fee, index) in fees" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ fee.term.year }}-{{ fee.term.name }}</td>
                    <td>{{ fee.course.name }}</td>
                    <td>{{ fee.amount }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <fee-dialog />
    </div>
</template>
<script>
import FeeDialog from "../components/fees/FeeDialog.vue";
export default {
    data() {
        return {};
    },
    computed: {
        fees() {
            return this.$store.state.fees.fees;
        },
    },
    mounted() {
        this.$store.dispatch("fees/actionList");
    },
    components: { FeeDialog },
};
</script>