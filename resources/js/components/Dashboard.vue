<template>

    <div class="container" id="dashboard">
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
                        <div class="card" id="dashcard">
                            <div class="card-header" id="dashcard-id">
                                <h5>{{idea.title}}</h5>
                            </div>
                            <div class="card-body" id="dashcard-body">
                                <p>{{idea.body}}</p>
                            </div>
                            <div class="card-footer" id="dashcard-footer">
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
                       <button type="button" @click="updateIdea" class="btn btn-primary">Opdatér</button>
                   </div>
               </div>
           </div>
       </div>
</div>
    
</template>


<script>

export default {

  // for hver v-model, skal der være et field.
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

  // Henter ideér når component bliver oprettet.
  created: function(none) {
        this.loadIdeas();
    },

  // Javascript metoder.
  methods: {
    
    // Når der klikkes på 'Tilføj idé', vises en modal-popup. 
    modalAdd: function(){
        this.idea.title = '';
        this.idea.body = '';
        $("#add_idea_model").modal("show");
    },

    // Når der klikkes på 'redigér', vises en modal-popup.
    // Samtidig sætter vi det lokale field, til at indeholde de informationer for den pågældene idé i arrayet, afhængig af indexet.
    modalUpdate: function(index){
        this.errors = [];
        $("#update_idea_model").modal("show");
        this.update_idea = this.ideas[index];
    },

    // Alle ideer bliver fetched og gemt i this.ideas arrayet. 
    loadIdeas: function() {
        axios.get('/api/dashboard/')
        .then((response)=>{
            this.ideas = response.data;
        })
        .catch((error)=>{
            this.ideas = 'An error occoured.' + error;
        });
    },

    //
    destroy: function(index){
        // Et confirm popup sikrer at brugeren ikke sletter en idé uhensigtsmæssigt. 
        let conf = confirm("Vil du skrotte denne Idé?");

        if (conf === true) {
            axios.delete('/api/idea/' + this.ideas[index].id)
                .then(response => {
                    // Fjerner elementet fra arrayet.
                    this.ideas.splice(index, 1);
                })
                .catch(error => {
                    console.log(error);
                })
        }
    },

    updateIdea: function(index){
        // Sender de opdaterede felter til controlleren.
        axios.patch('/api/idea/' + this.update_idea.id, {
            title: this.update_idea.title,
            body: this.update_idea.body
        })
        .then((response)=>{
            console.log(response);
        })
        .catch((error) => {
            alert("Udfyld venligst begge felter.");
            console.log(error);
        });

        $("#update_idea_model").modal("hide");
    },

    createIdea: function(index){
        axios.post('/api/idea', {
            title: this.idea.title,
            body: this.idea.body
        })
        .then((response)=>{
            // Resetter lokale fields.
            this.ideas.title = '';
            this.ideas.body = '';

            //Indsætter elementet først i arrayet.
            this.ideas.unshift(response.data.idea);
            $("#add_idea_model").modal("hide");
        })
        .catch((error)=>{
            alert("Udfyld venligst begge felter.");
            console.log(error);
        });
        
    }
  }
  
}
</script>

<style>
#dashboard{
    padding-bottom: 50px;
}

#dashcard{
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    background-color:#e4e5ec;
}

h5{
    font-weight:bold;
}

#dashcard:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

#dashcard-header{
    background-color: #e4e5ec;
}

#dashcard-body{
    background-color: rgb(255, 255, 255);
    padding: 5px 0px 0px 20px;
}

#dashcard-footer{
    background-color: rgb(255, 255, 255);
}

#panel{
    border: 1px solid lightgrey;
    padding: 3%;
    border-radius: 5px;
}
</style>
