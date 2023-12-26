 @extends('layouts.app')
 
 
 @section('main')
     <!-- Bootstrap CSS CDN -->
     <script>
     	$(document).ready(function(){
		// set up hover panels
		// although this can be done without JavaScript, we've attached these events
		// because it causes the hover to be triggered when the element is tapped on a touch device
		$('.hover').hover(function(){
			$(this).addClass('flip');
		},function(){
			$(this).removeClass('flip');
		});
	});</script>
<style>
     
h3 { color: #ffffff;  }

/*-=-=-=-=-=-=-=-=-=-*/
/* Column Grids */
/*-=-=-=-=-=-=-=-=-= */

 
.col_three_fourth { width: 74.5%;}
.col_twothird{ width: 66%;}
.col_half,
.col_third,
.col_twothird,
.col_fourth,
.col_three_fourth,
.col_fifth{
	position: relative;
	display:inline;
	display: inline-block;
	float: left;
	margin-right: 2%;
	margin-bottom: 20px;
}
.end { margin-right: 0 !important; }

/*-=-=-=-=-=-=-=-=-=-=- */
/* Flip Panel */
/*-=-=-=-=-=-=-=-=-=-=- */

 
.panel {
	margin: 0 auto;
	height: 130px;  
	position: relative;
	-webkit-perspective: 600px;
	-moz-perspective: 600px;
}

.panel .front,
.panel .back {
	text-align: center;
}
	
.panel .front {
	height: inherit;
	position: absolute;
	top: 0;
	z-index: 900;
	text-align: center;
	-webkit-transform: rotateX(0deg) rotateY(0deg);
	   -moz-transform: rotateX(0deg) rotateY(0deg);
	-webkit-transform-style: preserve-3d;
	   -moz-transform-style: preserve-3d;
	-webkit-backface-visibility: hidden;
	   -moz-backface-visibility: hidden;
	-webkit-transition: all .4s ease-in-out;
	   -moz-transition: all .4s ease-in-out;
		-ms-transition: all .4s ease-in-out;
		 -o-transition: all .4s ease-in-out;
			transition: all .4s ease-in-out;
}

.panel .back {
	height: inherit;
	position: absolute;
	top: 0;
	z-index: 1000;
	-webkit-transform: rotateY(-180deg);
	   -moz-transform: rotateY(-180deg);
	-webkit-transform-style: preserve-3d;
	   -moz-transform-style: preserve-3d;
	-webkit-backface-visibility: hidden;
	   -moz-backface-visibility: hidden;
	-webkit-transition: all .4s ease-in-out;
	   -moz-transition: all .4s ease-in-out;
		-ms-transition: all .4s ease-in-out;
		 -o-transition: all .4s ease-in-out;
			transition: all .4s ease-in-out;
}
.panel.flip .front {
	z-index: 900;
	-webkit-transform: rotateY(180deg);
	-moz-transform: rotateY(180deg);
}
.panel.flip .back {
	z-index: 1000;
	-webkit-transform: rotateX(0deg) rotateY(0deg);
	-moz-transform: rotateX(0deg) rotateY(0deg);
}
.box1{
	background-color: #0d6126;
	width: 250px;
	height: 150px;
	margin: 0 auto;
	padding: 20px;
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
}
.box2{
	background-color: #0d6126;
	width: 250px;
	height: 150px;
	margin: 0 auto;
	padding: 20px;
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
}
</style>
     
     <div class="py-12">
         <div class="lg:px-8">
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100 backgrounds">




                     <div class="container">
                         <div class="row">
                  
                             @foreach ($halisaha as $item)


 
                        
                               
                                  <div class="col-lg-3 mb-2">
                                  <div class="hover panel mb-4">
                                    <div class="front">
                                      <div class="box1">
                                        <h3 class="">{{ $item->name }}</h3>
                                      </div>
                                    </div>
                                    <div class="back">
                                      <div class="box2">
                                        <p class="card-text">     
                                             <a href="{{ route('calender.index', ['id' => $item->id]) }}"
                                                class="btn btn-primary m-2">Maç Ekle</a>
                                            <a href="{{ route('halisaha.editpage', ['id' => $item->id]) }}"
                                                class="btn btn-info m-2">Düzenle</a>
                                            <a href="{{ route('halisaha.delete', ['id' => $item->id]) }}"
                                                class="btn btn-danger m-2">Sil</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>





 





                              
                             @endforeach
                       

                         </div>
                     </div>

                 </div>
             </div>
         </div>
     </div>
 @endsection
