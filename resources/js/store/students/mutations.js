import axios from 'axios'
export default {
    SET_STUDENT(state, payload) {
        state.student = payload
    },
    SET_STUDENTS(state, payload) {
        state.students = payload
    },
    FETCH_STUDENTS(state) {
        axios.post('api/eschool/students').then((response) => {
            state.students = response.data.data
            state.filtered = state.students
        })
    },
    SET_FILTERED(state, payload) {
        state.filtered = payload
    },
    SET_META(state, payload) {
        state.meta = payload
    },
    ADD_STUDENT(state, payload) {
        state.students = [payload, ...state.students]
    },
    UPDATE_STUDENT(state, payload) {
        state.students = state.students.map((item) => {
            if (payload.id === item.id) {
                return payload
            }
            return item
        })
    },
}
