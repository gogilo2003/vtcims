<template>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-striped table-hovered">
                <thead class="">
                    <tr>
                        <th></th>
                        <th>Remarks</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in remarks" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.remark }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table
                class="
                    table
                    table-striped|sm|bordered|hover|inverse
                    table-inverse
                    table-responsive
                "
            >
                <thead class="thead-inverse|thead-default">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pre class="col-12">{{ remarks.list }}</pre>
    </div>
</template>
<script>
export default {
    data() {
        return {
            remark: {
                id: null,
                remark: null,
                grades: null,
            },
        };
    },
    computed: {
        remarks() {
            return this.$store.state.remarks.list;
        },
    },
    async mounted() {
        await this.$store.dispatch("remarks/list");
    },
};
</script>