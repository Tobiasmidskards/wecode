<template>

    <div class="container">
        <h3><span class="glyphicon glyphicon-dashboard"></span> Dashboard </h3> <br>
        <button class="btn btn-success"  @click="modalAdd()">
            Tilføj idé
        </button>
        <hr>
        <div v-for="(idea, index) in ideas" :key="idea.index">
          <div class="card">
            <div class="card-header">
              <h5><a v-bind:href="'./idea/' + idea.id">{{idea.title}}</a></h5>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <p>{{idea.body}}</p>
                <small>Oprettet: {{idea.created_at}} af {{idea.author}}</small>
              </li>
              <li class="list-group-item">
                <button class="btn btn-primary" @click="modalUpdate(index)">Redigér</button>
                <button class="btn btn-danger" @click="destroy(index)">Slet</button>
              </li> 
            </ul>
          </div>
          <br>
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
        axios.get('/ideazer/public/api/idea')
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