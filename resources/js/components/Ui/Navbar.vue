<template>
    <div class="font-sans antialiased" id="app">
        <nav class="flex items-center justify-between flex-wrap bg-green-500 p-6">
            <div class="flex items-center flex-no-shrink text-white mr-6">
                <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54"
                     xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/>
                </svg>
                <span class="font-semibold text-xl tracking-tight">Music App</span>
            </div>
            <div class="block sm:hidden">
                <button @click="toggle"
                        class="flex items-center px-3 py-2 border rounded text-teal-lighter border-teal-light hover:text-white hover:border-white">
                    <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>
                        Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                    </svg>
                </button>
            </div>
            <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center sm:w-auto">
                <div class="text-sm sm:flex-grow">
                    <router-link active-class="text-white"
                                 exact :to="{ name: 'songs.index' }"
                                 class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4">
                        Songs
                    </router-link>
                    <!--                    <a href="#responsive-header" class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4">-->
                    <!--                        Songs-->
                    <!--                    </a>-->
                    <router-link active-class="text-white"
                                 exact :to="{ name: 'admin.songs.index' }"
                                 v-if="user && user.is_admin" href="#responsive-header"
                                 class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4">
                        Manage songs
                    </router-link>
                    <router-link active-class="text-white"
                                 exact :to="{ name: 'admin.albums.index' }"
                                 v-if="user && user.is_admin" href="#responsive-header"
                                 class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4">
                        Manage albums
                    </router-link>
                    <router-link active-class="text-white"
                                 exact :to="{ name: 'admin.artists.index' }"
                                 v-if="user && user.is_admin" href="#responsive-header"
                                 class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4">
                        Manage artists
                    </router-link>
                </div>
                <div>
                    <button v-if="user" @click="logout">Logout</button>
                    <router-link v-else active-class="text-white"
                                 exact :to="{ name: 'auth.login' }"
                                 class="no-underline inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal hover:bg-green-300 mt-4 sm:mt-0">
                        Login
                    </router-link>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            open: false,
            user: null,
        }
    },
    methods: {
        toggle() {
            this.open = !this.open
        },
        logout() {
            const body = {};
            const headers = {
                "Authorization": "Bearer " + localStorage.getItem('token'),
            };
            axios.post("/api/logout", body, {headers})
                .then(response => this.articleId = response.data.id);
            this.$router.push("/songs");
            location.reload();
        }
    },
    mounted() {
        axios.get('/api/user', {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response => {
            this.user = response.data;
            console.log(response);

        }).catch(error => {
            console.log(error);
        });
    }
}
</script>
