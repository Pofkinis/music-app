<template>
    <div v-show="isAdmin" class="h-screen bg-gray-700 flex justify-center items-center">
        <div class="lg:w-2/5 md:w-1/2 w-2/3">
            <form @submit.prevent="submit_form" class="bg-green-500 p-10 rounded-lg shadow-lg min-w-full">
                <h1 class="text-2xl flex justify-center border-b-2 py-2 mb-4">Edit a song</h1>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Song name</label>
                    <input v-model="fields.name"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="name" id="songName" placeholder="Song name"/>
                    <div v-if="errors && errors.name"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.name[0] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Select album</label>
                    <select v-model="fields.album_id"
                            class="form-select block w-full mt-1 shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                        <option v-for="album in albums"
                                :value="album.id">{{ album.name }}</option>
                    </select>
                    <div>
                        <div v-if="errors && errors.album_id"
                             class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                            <div class=" font-normal  max-w-full flex-initial">
                                {{ errors.album_id[0] }}
                            </div>
                        </div>
                    </div>

                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Link to song in Youtube</label>
                    <input v-model="fields.link_to_song"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="username" id="username" placeholder="Link to song"/>
                    <div v-if="errors && errors.link_to_song"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.link_to_song[0] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Song type</label>
                    <input v-model="fields.type"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="username" id="username" placeholder="Song type"/>
                    <div v-if="errors && errors.type"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.type[0] }}
                        </div>
                    </div>
                </div>
                <button type="submit"
                        class="w-full mt-6 mb-3 bg-gray-500 hover:bg-gray-600 rounded-lg px-4 py-2 text-lg text-white tracking-wide font-semibold font-sans">
                    Save changes
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
            albums: {},
            fields: {
                name: '',
                link_to_song: '',
                type: '',
                album_id: '',
            },
            errors: {},
            form_submitting: false,
            isAdmin: false,
        }
    },
    mounted() {
        axios.get('/api/albums')
            .then(response => {
                this.albums = response.data.data;
            });

        axios.get('/api/songs/' + this.$route.params.id)
            .then(response => {
                this.fields = response.data;
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
            axios.put('/api/songs/' + this.$route.params.id, this.fields, {headers})
                .then(response => {
                    this.form_submitting = false;
                    const notyf = new Notyf();
                    notyf.success('Song updated successfully');
                    this.$router.push('/admin/songs');
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
