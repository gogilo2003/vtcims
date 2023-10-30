import Dashboard from './views/Fees.vue'
import Fees from './views/Fees.vue'
import FeeTransactions from './views/Fees.vue'

export default {
    routes: [{
        path: "/",
        component: Dashboard
    }, {
        path: '/fees',
        component: Fees
    }, {
        path: '/fee-transactions',
        component: FeeTransactions
    }]
}