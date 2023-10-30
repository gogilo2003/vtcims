import axios from 'axios'
export default {
    SET_STUDENT(state, payload) {
        state.student = payload
    },
    SET_STUDENTS(state, payload) {
      state.students = payload
    },
    FETCH_STUDENTS(state){
        axios.post(route('api-eschool-students')).then((response) => {
            state.students = response.data.data
            state.filtered = state.students
        })
    },
    SET_FILTERED(state,payload){
        state.filtered = payload
    }
}
