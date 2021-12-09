import SongsIndex from '../components/Songs/Index.vue'
import SongsShow from '../components/Songs/Show.vue'
import AuthLogin from '../components/Auth/Login.vue'
import AdminSongsCreate from '../components/Admin/Songs/Create.vue'
import AdminSongsIndex from '../components/Admin/Songs/Index.vue'
import AdminSongsEdit from '../components/Admin/Songs/Edit.vue'
import AdminAlbumsIndex from '../components/Admin/Albums/Index.vue'
import AdminAlbumsCreate from '../components/Admin/Albums/Create.vue'
import AdminAlbumsEdit from '../components/Admin/Albums/Edit.vue'
import AdminArtistsIndex from '../components/Admin/Artists/Index.vue'
import AdminArtistsCreate from '../components/Admin/Artists/Create.vue'
import AdminArtistsEdit from '../components/Admin/Artists/Edit.vue'

export default {
    mode: 'history',
    routes: [
        {
            path: '/songs',
            component: SongsIndex,
            name: 'songs.index'
        },
        {
            path: '/songs/:id',
            component: SongsShow,
            name: 'songs.show'
        },
        {
            path: '/login',
            component: AuthLogin,
            name: 'auth.login'
        },
        {
            path: '/admin/songs/create',
            component: AdminSongsCreate,
            name: 'admin.songs.create'
        },
        {
            path: '/admin/songs',
            component: AdminSongsIndex,
            name: 'admin.songs.index'
        },
        {
            path: '/admin/songs/edit/:id',
            component: AdminSongsEdit,
            name: 'admin.songs.edit'
        },
        {
            path: '/admin/albums/create',
            component: AdminAlbumsCreate,
            name: 'admin.albums.create'
        },
        {
            path: '/admin/albums',
            component: AdminAlbumsIndex,
            name: 'admin.albums.index'
        },
        {
            path: '/admin/albums/edit/:id',
            component: AdminAlbumsEdit,
            name: 'admin.albums.edit'
        },
        {
            path: '/admin/artists/create',
            component: AdminArtistsCreate,
            name: 'admin.artists.create'
        },
        {
            path: '/admin/artists',
            component: AdminArtistsIndex,
            name: 'admin.artists.index'
        },
        {
            path: '/admin/artists/edit/:id',
            component: AdminArtistsEdit,
            name: 'admin.artists.edit'
        },
    ]
}
