import List from '../pages/List.vue'
import Create from '../pages/Create.vue'

export default {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'list',
            component: List,
        },
        {
            path: '/create',
            name: 'create',
            component: Create,
        }
    ]
}
