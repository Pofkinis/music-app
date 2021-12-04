<template>
    <div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            songs: []
        }
    },
    mounted() {
        axios.get('/api/songs').then(response => {
            console.log(response);
            this.songs = response.data.data;
        });
        console.log(this.songs);
    }
}
</script>
