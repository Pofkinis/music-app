<template>
    <div class="max-w-3xl mx-auto">
        <div class="bg-green-500 text-gray-200 shadow-md rounded-lg max-w-full m-5">
            <img class="rounded-t-lg w-full"
                 v-bind:src="this.song.album.image_link" alt="">
            <div class="p-4 mb:p-16">
                <h5 class="pb-5 text-center font-bold text-2xl tracking-tight mb-2">{{ this.song.name }}</h5>
                <div class="flex justify-around w-full">
                    <div>
                        <p class="font-normal mb-3">Artist: <b>{{ song.album.artist.first_name }}
                            {{ song.album.artist.last_name }}</b></p>
                        <p class="font-normal mb-3">Album: <b>{{ song.album.name }}</b></p>
                    </div>

                    <div>
                        <p class="font-normal mb-3">Genre: <b>{{ song.type }}</b></p>
                        <p class="font-normal mb-3">Album release: <b>{{ song.album.release_date }}</b></p>
                    </div>
                </div>

                <!-- comment form -->
                <div v-if="user" class="flex mx-auto items-center justify-center shadow-lg mt-4 mx-8 mb-4 max-w-lg">
                    <form @submit.prevent="storeComment" class="w-full max-w-xl bg-green-400 rounded-lg px-4 pt-2">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <h2 class="px-4 pt-3 pb-2 text-white text-lg">Add a new comment</h2>
                            <div class="w-full md:w-full px-3 mb-2 mt-2">
                                <textarea v-model="comment"
                                          class="rounded text-green-900 border leading-normal resize-none w-full h-20 py-2 px-3 font-medium focus:outline-none"
                                          name="body" placeholder='Type Your Comment' required></textarea>
                            </div>
                            <div class="w-full md:w-full flex items-start md:w-full px-3">
                                <div class="flex items-start w-1/2 text-gray-700 px-2 mr-auto">
                                </div>
                                <div class="-mr-1">
                                    <button type='submit'
                                            class="px-4 py-2 rounded text-white inline-block shadow-lg bg-gray-500 hover:bg-gray-600 focus:bg-gray-700">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div v-for="comment in comments.data ">
                    <div class="flex justify-center relative top-1/3 w-full md:px-24">
                        <!-- This is an example component -->
                        <div
                            class="relative grid grid-cols-1 gap-4 p-4 mb-8 border rounded-lg bg-white w-full shadow-lg break-all">
                            <div class="relative flex gap-4">
                                <div class="flex flex-col w-full">
                                    <div class="flex flex-row justify-between">
                                        <p class="relative text-green-800 text-xl whitespace-nowrap truncate overflow-hidden">
                                            {{ comment.user.name }}</p>
                                        <a class="text-gray-500 text-xl" href="#"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                            <p class="-mt-4 text-gray-500">{{ comment.comment }}</p>
                            <button v-if="user && (user.is_admin || user.id == comment.user_id)" @click="deleteComment(comment.id)" class="flex justify-content-end text-red-300">delete</button>
                        </div>
                    </div>
                </div>
                <div>
                    <pagination class="flex justify-center flex-row space-x-4" :data="comments"
                                @pagination-change-page="getResults"></pagination>
                </div>


                <div class="text-center p-5">
                    <router-link active-class="text-white"
                                 exact :to="{ name: 'songs.index' }"
                                 class="px-4 py-2 rounded text-white inline-block shadow-lg bg-gray-500 hover:bg-gray-600 focus:bg-gray-700">
                        Close
                    </router-link>
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
            song: Object,
            comments: {},
            comment: null,
            user: null,
            imageLink: ""
        }
    },
    mounted() {
        axios.get('/api/songs/' + this.$route.params.id)
            .then(response => {
                this.song = response.data;
                // this.imageLink = ;
            });

        this.getResults();

        axios.get('/api/user', {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        }).then(response => {
            this.user = response.data;
        });
        // console.log("asdasdsadad");
        // console.log(this.song.album.name);
    },
    methods: {
        getResults(page = 1) {
            axios.get('/api/comments/' + this.$route.params.id + '?page=' + page)
                .then(response => {
                    this.comments = response.data;
                });
        },
        storeComment() {
            const headers = {
                "Authorization": "Bearer " + localStorage.getItem('token'),
            };
            axios.post('/api/comments', {
                'comment': this.comment,
                'song_id': this.$route.params.id
            }, {headers})
                .then(response => {
                    this.getResults();
                    this.comment = null;
                    const notyf = new Notyf();
                    notyf.success('You have successfully commented a song!');
                });
        },
        deleteComment(id) {
            const headers = {
                "Authorization": "Bearer " + localStorage.getItem('token'),
            };
            axios.delete('/api/comments/' + id, {headers})
                .then(response => {
                    this.getResults();
                    const notyf = new Notyf();
                    notyf.success('Comment successfully deleted!');
                });
        }
    }
}
</script>
