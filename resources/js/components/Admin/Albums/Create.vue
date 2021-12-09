<template>
    <div v-show="isAdmin" class="h-screen bg-gray-700 flex justify-center items-center">
        <div class="lg:w-2/5 md:w-1/2 w-2/3">
            <form @submit.prevent="submit_form" class="bg-green-500 p-10 rounded-lg shadow-lg min-w-full">
                <h1 class="text-2xl flex justify-center border-b-2 py-2 mb-4">Create a new album</h1>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Album name</label>
                    <input v-model="fields.name"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="name" id="songName" placeholder="Album name"/>
                    <div v-if="errors && errors.name"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.name[0] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Select artist</label>
                    <select v-model="fields.artist_id"
                        class="form-select block w-full mt-1 shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                        <option v-for="artist in artists"
                                :value="artist.id">{{ artist.first_name }} {{ artist.last_name }}</option>
                    </select>
                    <div>
                    <div v-if="errors && errors.album_id"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.album_id[0] }}
                        </div>
                    </div>
                </div>

                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Album cover image</label>
                    <input v-model="fields.image_link"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="username" id="username" placeholder="Album image"/>
                    <div v-if="errors && errors.image_link"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.image_link[0] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Album release date (yyyy-mm-dd)</label>
                    <input v-model="fields.release_date"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="username" id="username" placeholder="Release date"/>
                    <div v-if="errors && errors.release_date"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.release_date[0] }}
                        </div>
                    </div>
                </div>
                <button type="submit"
                        class="w-full mt-6 mb-3 bg-gray-500 hover:bg-gray-600 rounded-lg px-4 py-2 text-lg text-white tracking-wide font-semibold font-sans">
                    Create
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

export default {
    data() {
        return {
            artists: {},
            fields: {
                name: '',
                image_link: '',
                release_date: '',
                artist_id: '',
            },
            errors: {},
            form_submitting: false,
            isAdmin: false,
        }
    },
    mounted() {
        axios.get('/api/artists')
            .then(response => {
                this.artists = response.data.data;
            });
    },
    methods: {
        submit_form() {
            this.form_submitting = true;
            let fields = new FormData();
            for (let key in this.fields) {
                fields.append(key, this.fields[key]);
            }
            const headers = {
                "Authorization": "Bearer " + localStorage.getItem('token'),
            };
            axios.post('/api/albums', fields, {headers})
                .then(response => {
                    this.$router.push('/');
                    this.form_submitting = false;
                    const notyf = new Notyf();
                    notyf.success('Album created successfully');
                    this.getResults();
                    this.$router.push("/admin/albums");
                }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            });
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
