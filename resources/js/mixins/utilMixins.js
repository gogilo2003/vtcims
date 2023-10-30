export default {
    methods: {
        /**
         * 
         * @param {object|array} errors 
         * @returns 
         */
        errorList(errors) {
            console.log(typeof(errors))
            let list = `<ol>`

            for (const item in errors) {
                if (Object.hasOwnProperty.call(errors, item)) {
                    const element = errors[item];
                    list += `<li>${element}</li>`
                }
            }
            list += `</ol>`

            return list
        }
    }
}