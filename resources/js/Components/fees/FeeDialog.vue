<template>
    <div>
        <!-- Button trigger modal -->

        <!-- Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="modal-title card-title">Fees Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body card-body">
                            <div class="form-group">
                                <label class="bmd-label-static" for="term">Term</label>
                                <select class="form-control selectpicker" name="term" id="term"
                                    data-style="btn btn-link" v-model="payload.term">
                                    <option v-for="term in terms" :key="term.id" :value="term.id">
                                        {{ term.year }}-{{ term.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="bmd-label-static" for="term">Course</label>
                                <select class="form-control selectpicker" name="course" id="course"
                                    data-style="btn btn-link" v-model="payload.course">
                                    <option v-for="course in courses" :key="course.id" :value="course.id">
                                        {{ course.department.code }}|{{
                                        course.name
                                    }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="bmd-label-static" for="amount">Amount</label>
                                <input type="text" class="form-control" name="amount" id="amount"
                                    aria-describedby="amountHelpId" v-model="payload.amount" />
                            </div>
                        </div>
                        <div class="modal-footer card-footer">
                            <button type="button" class="btn btn-outline-danger rounded-pill" data-dismiss="modal">
                                Close
                            </button>
                            <button type="button" class="btn btn-primary rounded-pill" @click="save">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import utilMixins from "../../mixins/utilMixins";
export default {
    data() {
        return {
            payload: {
                course: null,
                term: null,
                amount: null,
            },
        };
    },
    computed: {
        fee() {
            this.payload = this.$store.state.fees.fee;
            return this.$store.state.fees.fee;
        },
        terms() {
            return this.$store.state.terms.terms;
        },
        courses() {
            return this.$store.state.courses.courses;
        },
    },
    created() {
        this.$store.dispatch("terms/actionList").then(() => {
            $("#term").selectpicker("refresh");
        });
        this.$store.dispatch("courses/actionList").then(() => {
            $("#course").selectpicker("refresh");
        });
    },
    methods: {
        save() {
            this.$store
                .dispatch("fees/actionStore", this.payload)
                .then((response) => {
                    if (response.success) {
                        $.notify(
                            {
                                message: response.message,
                            },
                            {
                                type: "success",
                                z_index: 9999,
                            }
                        );
                    } else {
                        if (response.status == 415) {
                            $.notify(
                                {
                                    title: "Message " + response.data.message,
                                    message: this.errorList(
                                        response.data.details
                                    ),
                                },
                                {
                                    type: "danger",
                                    z_index: 9999,
                                }
                            );
                        }
                    }
                })
                .catch((error) => {
                    if (error.response.status == 415) {
                        $.notify(
                            {
                                title: "Message " + error.response.data.message,
                                message: this.errorList(
                                    error.response.data.details
                                ),
                            },
                            {
                                type: "danger",
                                z_index: 9999,
                                duration: 5000,
                            }
                        );
                    }
                });
        },
    },
    mixins: [utilMixins],
};
</script>
