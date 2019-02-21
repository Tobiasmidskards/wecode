<template>
    <div class="container">
        <h1>Feed</h1>
        <div v-for="(idea) in data" :key="idea.index">
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
    
</template>

<script>
export default {
  data() {
    return {
      data:'',
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
        this.data = response.data;

      })
      .catch(function (error){
        this.data = 'An error occoured.' + error;
      });
    },

    destroy: function(index){
      let conf = confirm("Vil du gerne skrotte denne Idé");

      if (conf === true) {
        axios.delete('/ideazer/public/api/idea/' + this.data[index].id)
          .then(response => {

          })
          .catch(error => {

          })
      }
    }
  }
  
}
</script>