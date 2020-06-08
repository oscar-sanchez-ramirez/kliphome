<template>
 <v-app>
    <h3>Calificaciones</h3>
    <div v-for="item in reviews" :key="item.id" >
         <a data-toggle="collapse"  :href="'#collapseExample'+item.id" role="button" aria-expanded="false" :aria-controls="'collapseExample'+item.id">{{ average(item.presentation,item.problemSolve,item.puntuality) }} <span v-html="star_function(average(item.presentation,item.problemSolve,item.puntuality))"></span></a>
           <div class="collapse" :id="'collapseExample'+item.id">
                <div class="card card-body">
                    <div class="row">
                        <b>Presentación:  </b> <span v-html="star_function(Number(item.presentation))"></span>
                    </div>
                    <div class="row">
                        <b>Puntualidad  :  </b>  <span v-html="star_function(Number(item.puntuality))"></span>
                    </div>
                    <div class="row">
                        <b>Solución al problema:  </b> <span v-html="star_function(Number(item.problemSolve))"></span>
                    </div>
                    <div class="row">
                        <b>Comentario  :  </b> {{ item.comment || "-" }}
                    </div>
                </div>
            </div>
    </div>

 </v-app>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
    .checked {
        color: orange !important;
    }
    .fa-star{
        color:#3333;
    }
</style>
<script>
export default{
  data(){
      return {
          reviews:[]
      }
  },
  methods:{
      average(presentation,problemSolve,puntuality){
          return ((Number(presentation) + Number(problemSolve) + Number(puntuality)) / 3).toFixed(1);
      },
      star_function(val){
            if(val < 1.5){
                return '<span class="fa fa-star checked"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
            }
            if(val > 1.5 && val < 2.5){
                return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
            }
            if(val > 2.5 && val < 3.5){
                return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span><span class="fa fa-star"></span>';
            }
            if(val > 3.5 && val < 4.5){
                return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span>';
            }
            if(val > 4.5){
                return '<span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star checked"></span><span class="fa fa-star"></span>';
            }
        }
  },props:{
    delegation: Array,
    categories: Array,
    fixerman: Object
  },
  mounted(){
      axios.get('/tecnicos/reviews/'+this.fixerman.id).then(response => {
            //   this.loader = false;
              console.log(response.data);
              this.reviews = response.data.reviews;
            }).catch(error => {
            //   this.loader = false;
            });
  },
}
</script>
