<template>
    <div class="col-md-8 mt-4">
        <div class="card card-default">
            <div class="card-header">Idea Form</div>

            <div class="card-body">
                <form @submit.prevent="addIdea()">
                    <div class="form-group">
                        <input type="text" name="title" v-model="title" placeholder="Idea Title" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="body" v-model="body" placeholder="Idea body" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Add idea" class="btn btn-info">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data(){
        return {
            title:'',
            body:''
        }
    },
    mounted() {
        console.log('Component mounted')
    },

    methods:{
        addIdea(){
            axios.post('/ideazer/public/api/idea', {
                title: this.title,
                body: this.body
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
}
</script>
