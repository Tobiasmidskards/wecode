<template>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="panel">
                    <h2> Dashboard </h2>
                    <button class="btn btn-success"  @click="modalAdd()">
                        Tilføj idé
                    </button>
                    <br>
                    <hr>
                    <div v-for="(idea, index) in ideas" :key="idea.index">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{idea.title}}</h5>
                            </div>
                            <div class="card-body">
                                <p>{{idea.body}}</p>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary btn-sm" @click="modalUpdate(index)">Redigér</button>
                                <button class="btn btn-danger btn-sm" @click="destroy(index)">Slet</button>
                                <small class="float-right">{{idea.created_at}}</small>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="modal fade" tabindex="-1" role="dialog" id="add_idea_model">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h4 class="modal-title">Tilføj idé</h4>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                               aria-hidden="true">&times;</span></button>
                       </div>
                   <div class="modal-body">
                       <div class="alert alert-danger" v-if="errors.length > 0">
                           <ul>
                               <li v-for="error in errors" :key="error">{{ error }}</li>
                           </ul>
                       </div>
                       <div class="form-group">
                           <label for="titles">Titel:</label>
                           <input type="text" name="idea" id="idea" placeholder="Titel" class="form-control"
                                  v-model="idea.title">
                       </div>
                       <div class="form-group">
                           <label for="body">Beskrivelse:</label>

                           <textarea name="body" id="body" cols="30" rows="5" class="form-control"
                                     placeholder="Beskrivelse" v-model="idea.body"></textarea>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
                       <button type="button" @click="createIdea" class="btn btn-primary">Opret</button>
                   </div>
               </div>
           </div>
       </div>


       <div class="modal fade" tabindex="-1" role="dialog" id="update_idea_model">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h4 class="modal-title">Redigér idé</h4>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                               aria-hidden="true">&times;</span></button>
                       </div>
                   <div class="modal-body">
                       <div class="alert alert-danger" v-if="errors.length > 0">
                           <ul>
                               <li v-for="error in errors" :key="error">{{ error }}</li>
                           </ul>
                       </div>
                       <div class="form-group">
                           <label for="titles">Titel:</label>
                           <input type="text" name="idea" id="idea" placeholder="Titel" class="form-control"
                                  v-model="update_idea.title">
                       </div>
                       <div class="form-group">
                           <label for="body">Beskrivelse:</label>

                           <textarea name="body" id="body" cols="30" rows="5" class="form-control"
                                     placeholder="Beskrivelse" v-model="update_idea.body"></textarea>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
                       <button type="button" @click="updateIdea" class="btn btn-primary">Opret</button>
                   </div>
               </div>
           </div>
       </div>
</div>
    
</template>


<script>

export default {
  data() {
    return {
      idea:{
          title: '',
          body: ''
      },
      ideas:[],
      update_idea:{},
      errors:[]
    }
  },

  // Fetches posts when the component is created.
  created: function(none) {
        this.loadIdea();
    },

  methods: {
    modalAdd: function(){
        $("#add_idea_model").modal("show");

    },

    modalUpdate: function(index){
        this.errors = [];
        $("#update_idea_model").modal("show");
        this.update_idea = this.ideas[index];
    },

    loadIdea: function() {
        this.data = 'Loading..';
        axios.get('/ideazer/public/api/dashboard/')
        .then((response)=>{
        this.ideas = response.data;
        })
        .catch(function (error){
        this.ideas = 'An error occoured.' + error;
        });
    },

    destroy: function(index){
        let conf = confirm("Vil du gerne skrotte denne Idé");

        if (conf === true) {
        axios.delete('/ideazer/public/api/idea/' + this.ideas[index].id)
            .then(response => {
                this.ideas.splice(index, 1);
            })
            .catch(error => {

            })
        }
    },

    updateIdea: function(index){
        axios.patch('/ideazer/public/api/idea/' + this.update_idea.id, {
            title: this.update_idea.title,
            body: this.update_idea.body
        })
        .then(function (response){
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });

        $("#update_idea_model").modal("hide");
    },

    createIdea: function(index){
        axios.post('/ideazer/public/api/idea', {
            title: this.idea.title,
            body: this.idea.body
        })
        .then((response)=>{
            this.ideas.title = '';
            this.ideas.body = '';
            this.ideas.unshift(response.data.idea);
            $("#add_idea_model").modal("hide");
        })
        .catch(function (error) {
            console.log(error);
        });
        
    }
  }
  
}
</script>

<style>
.container{
    padding-bottom: 50px;
}

.card{
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    background-color:#e4e5ec;
}

h5{
    font-weight:bold;
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.card-header{
    background-color: #e4e5ec;
}

.card-body{
    background-color: rgb(255, 255, 255);
    padding: 5px 0px 0px 20px;
}

.card-footer{
    background-color: rgb(255, 255, 255);
}

#panel{
    border: 1px solid lightgrey;
    padding: 3%;
    border-radius: 5px;
}
</style>
