<template>
    <div v-show="isAdmin" class="w-screen h-full">
        <div class="text-3xl text-center mb-5 mt-4">Admin songs management</div>
        <div class="grid justify-items-end mr-48">
            <router-link :to="{ name: 'admin.songs.create' }" class="px-4 py-2 rounded text-white shadow-lg bg-gray-500 hover:bg-gray-600 focus:bg-gray-700">Create new song</router-link>
        </div>
        <div class="grid justify-items-center relative">
                <div class="relative text-gray-600 focus-within:text-gray-400">
      <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <button class="p-1 focus:outline-none focus:shadow-outline">
          <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               viewBox="0 0 24 24" class="w-6 h-6"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
      </span>
                    <input type="search" name="q" v-model="search"
                           class="py-2 text-sm text-white bg-gray-900 rounded-md pl-10 focus:outline-none focus:bg-white focus:text-gray-900"
                           placeholder="Search..." autocomplete="off">
                </div>
        </div>
        <div class="flex border-b border-gray-800 font-bold lg:mx-52">
            <a href="#" class="p-3 w-full">NAME</a>
            <a href="#" class="p-3 w-full">ARTIST</a>
            <a href="#" class="p-3 w-full">ALBUM</a>
            <a href="#" class="p-3 w-full hidden md:block">GENRE</a>
            <a href="#" class="p-3 w-full">ACTIONS</a>
        </div>
        <div v-for="song in this.songs.data" class="flex border-b border-gray-800 hover:bg-gray-800 lg:mx-52">
            <div class="p-3 w-full">{{ song.name }}</div>
            <div class="p-3 w-full">{{ song.album.artist.first_name }} {{ song.album.artist.last_name }}</div>
            <div class="p-3 w-full">{{ song.album.name }}</div>
            <div class="p-3 w-full hidden md:block">{{ song.type }}</div>
            <div class="p-3 w-full">
                <router-link :to="{ name: 'admin.songs.edit', params: { id: song.id } }"
                             class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4 text-blue-500">
                    Edit
                </router-link>
                <button @click="openModal(song.id)"
                        class="no-underline block mt-4 sm:inline-block sm:mt-0 text-teal-lighter hover:text-white mr-4 text-red-600">
                    Delete
                </button>
            </div>
        </div>


        <pagination class="flex justify-center flex-row space-x-4" :data="songs"
                    @pagination-change-page="getResults"></pagination>

        <div v-show="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete a song
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete this song?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="deleteSong" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button @click="showModal = false" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Notyf} from "notyf";
export default {
    data() {
        return {
            albums: {},
            songs: {},
            showModal: false,
            linkToSong: "",
            deletingSongId: null,
            search: '',
            isAdmin: false,
        }
    },
    mounted() {
        axios.get('/api/albums')
            .then(response => {
                this.albums = response.data.data;
            });
        this.getResults();
    },
    methods: {
        getResults(page = 1) {
            axios.get('/api/songs?page=' + page, {
                params: {
                    page,
                    search: this.search.length >= 4 ? this.search : ''
                }
            }).then(response => {
                this.songs = response.data;
            });
        },
        openModal(songId) {
            this.showModal = true;
            this.deletingSongId = songId;
        },
        deleteSong() {
            const headers = {
                "Authorization": "Bearer " + localStorage.getItem('token'),
            };
            axios.delete('/api/songs/' + this.deletingSongId, {headers});
            this.showModal = false;
            this.getResults();
            this.$router.push("/admin/songs");
            const notyf = new Notyf();
            notyf.success('You have successfully deleted a song!');
        },
    },
    watch: {
        search (val, old) {
            if (val.length >= 4 || old.length >= 4) {
                this.getResults();
            }
        }
    },
    beforeMount() {
        axios.get('/api/user', {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response => {
            if(response.data.is_admin){
                this.isAdmin = true;
            }
            else{
                this.$router.push('/');
            }
        }).catch(error => {
            this.$router.push('/');
        });
    }
}
</script>
