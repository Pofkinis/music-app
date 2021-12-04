import SongsIndex from '../components/Songs/Index.vue'

export default {
    mode: 'history',
    routes: [
        {
            path: '/songs',
            component: SongsIndex,
            name: 'songs.index'
        },
    ]
}
