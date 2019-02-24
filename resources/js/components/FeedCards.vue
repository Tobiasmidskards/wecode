<template>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Feed</h1>
          <div v-if="ideas.length > 0">
            <div v-for="(idea) in ideas" :key="idea.index">
              <div class="card">
                <div class="card-header">
                  <h5>{{idea.title}}</h5>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    <p>{{idea.body}}</p>
                    <small>Oprettet: {{idea.created_at}} af {{idea.author}}</small>
                  </li>
                </ul>
              </div>
              <br>
            </div>
          </div>
          <div v-else>
            <div class="alert alert-secondary">
              I er løbet tør for ideér.
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
      ideas:[]
    }
  },

  // Fetches posts when the component is created.
  created: function() {
    this.loadIdea();
  },

  methods: {
    loadIdea: function() {
      this.data = 'Loading..';
      axios.get('/ideazer/public/api/idea')
      .then((response)=>{
        this.ideas = response.data;

      })
      .catch((error)=>{
        console.log(error);
      });
    },

    destroy: function(index){
      let conf = confirm("Vil du gerne skrotte denne Idé");

      if (conf === true) {
        axios.delete('/ideazer/public/api/idea/' + this.data[index].id)
          .then(response => {
            console.log(response);
          })
          .catch(error => {
            console.log(error);
          })
      }
    }
  }
  
}
</script>