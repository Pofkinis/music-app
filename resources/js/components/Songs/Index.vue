<template>
    <div class="w-screen h-full">

        <header class="text-center w-full bg-gray-800 text-green-400 text-4xl p-12 mb-8">
            Welcome to the Music App
        </header>

        <div class="grid justify-items-center relative">

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

        </div>

        <div class="flex border-b border-gray-800 font-bold lg:mx-52">
            <div class="p-3 w-8 flex-shrink-0"></div>
            <div class="p-3 w-full">NAME</div>
            <div class="p-3 w-full">ARTIST</div>
            <div class="p-3 w-full hidden md:block">ALBUM</div>
            <div class="p-3 w-full hidden md:block">GENRE</div>
            <div class="p-3 w-12"></div>
            <div class="p-3 w-12"></div>
        </div>
        <div v-for="song in this.songs.data" class="flex border-b border-gray-800 hover:bg-gray-800 lg:mx-52">
            <div class="p-3 w-8 flex-shrink-0">❤️</div>
            <div class="p-3 w-full">{{ song.name }}</div>
            <div class="p-3 w-full">{{ song.album.artist.first_name }} {{ song.album.artist.last_name }}</div>
            <div class="p-3 w-full hidden md:block">{{ song.album.name }}</div>
            <div class="p-3 w-full hidden md:block">{{ song.type }}</div>
            <router-link :to="{ name: 'songs.show', params: { id: song.id } }" class="p-3 w-12 flex-shrink-0">
                👁️‍🗨
            </router-link>
            <button @click="openSongModal(song.link_to_song)" class="p-3 w-12 flex-shrink-0">▶️</button>
        </div>

        <div v-show="showSongModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="justify-center sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        <iframe v-bind:src="linkToSong"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                        </iframe>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showSongModal = false" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
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
            songs: [],
            showSongModal: false,
            linkToSong: "",
            search: ''
        }
    },
    mounted() {
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
        openSongModal(link) {
            this.linkToSong = link;
            this.showSongModal = true;
        }
    },
    watch: {
        search (val, old) {
            if (val.length >= 4 || old.length >= 4) {
                this.getResults();
            }
        }
    },
}
</script>
