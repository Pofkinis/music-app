<template>
    <div v-show="isAdmin" class="h-screen bg-gray-700 flex justify-center items-center">
        <div class="lg:w-2/5 md:w-1/2 w-2/3">
            <form @submit.prevent="submit_form" class="bg-green-500 p-10 rounded-lg shadow-lg min-w-full">
                <h1 class="text-2xl flex justify-center border-b-2 py-2 mb-4">Edit an artist</h1>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Artist first name</label>
                    <input v-model="fields.first_name"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="name" id="songName" placeholder="First name"/>
                    <div v-if="errors && errors.first_name"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.first_name[0] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Artist last name</label>
                    <input v-model="fields.last_name"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="username" id="username" placeholder="Last name"/>
                    <div v-if="errors && errors.last_name"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.last_name[0] }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-normal mb-2 mt-4" for="username">Artist birth date (yyyy-mm-dd)</label>
                    <input v-model="fields.birth_date"
                           class="shadow text-green-500 appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                           type="text" name="username" id="username" placeholder="Birth date"/>
                    <div v-if="errors && errors.birth_date"
                         class="flex items-center m-1 font-medium py-1 px-2 bg-white rounded-md text-red-700 bg-red-100 border border-red-300 ">
                        <div class=" font-normal  max-w-full flex-initial">
                            {{ errors.birth_date[0] }}
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
            fields: {
                first_name: '',
                last_name: '',
                birth_date: '',
            },
            errors: {},
            form_submitting: false,
            isAdmin: false,
        }
    },
    mounted() {
        axios.get('/api/artists/' + this.$route.params.id)
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
            axios.put('/api/artists/' + this.$route.params.id, this.fields, {headers})
                .then(response => {
                    this.form_submitting = false;
                    const notyf = new Notyf();
                    notyf.success('Artist updated successfully');
                    this.$router.push('/admin/artists');
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
            this.isAdmin = true;
        }).catch(error => {
            this.$router.push('/');
        });
    }
}
</script>
