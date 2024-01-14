import axios from 'axios'
export default {
    getStudent(state, id) {
        // if (id) {
        //     axios.post('/api/eschool/students/student', id).then((response) => {
        //         state.student = response.data
        //         return state.student
        //     }, (error) => {
        //         console.log(error)
        //     })
        // } else {
        //     return state.student
        // }
    },
    getStudents(state) {
        return state.students
    },
    getFiltered(state) {
        return state.filtered
    }
}