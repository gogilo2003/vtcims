export default {
    setStudentAction(context, payload) {
        context.commit('SET_STUDENT', payload)
    },
    fetchStudents(context, payload=false){
        context.commit('FETCH_STUDENTS',payload)
    },
    setFiltered(context,payload){
        context.commit('SET_FILTERED',payload)
    }
}
