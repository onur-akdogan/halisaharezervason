 @extends('layouts.app')


 @section('main')
     <!-- Bootstrap CSS CDN -->
     <script>
         $(document).ready(function() {
             // set up hover panels
             // although this can be done without JavaScript, we've attached these events
             // because it causes the hover to be triggered when the element is tapped on a touch device
             $('.hover').hover(function() {
                 $(this).addClass('flip');
             }, function() {
                 $(this).removeClass('flip');
             });
         });
     </script>
     <style>
         h3 {
             color: #ffffff;
         }

         /*-=-=-=-=-=-=-=-=-=-*/
         /* Column Grids */
         /*-=-=-=-=-=-=-=-=-= */


         .col_three_fourth {
             width: 74.5%;
         }

         .col_twothird {
             width: 66%;
         }

         .col_half,
         .col_third,
         .col_twothird,
         .col_fourth,
         .col_three_fourth,
         .col_fifth {
             position: relative;
             display: inline;
             display: inline-block;
             float: left;
             margin-right: 2%;
             margin-bottom: 20px;
         }

         .end {
             margin-right: 0 !important;
         }

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



         .panel.flip .back {
             z-index: 1000;
             -webkit-transform: rotateX(0deg) rotateY(0deg);
             -moz-transform: rotateX(0deg) rotateY(0deg);
         }

         .box1 {
             background-color: #0d6126;
        
             margin: 0 auto;
             padding: 20px;
             border-radius: 10px;
             -moz-border-radius: 10px;
             -webkit-border-radius: 10px;
         }
      
     </style>


     <div class="py-12">
         <div class="lg:px-12">
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100 backgrounds">





                     <h3 style="color: #0d6126">
                         Sahalarım
                     </h3>

                  <div class="col-lg-12">
                    <div class="row">

                        @foreach ($halisaha as $item)
                            <div class="col-lg-3 mt-2">
 
                                    <div class="box1">
                                        <x-dropdown   width="48">
                                            <x-slot name="trigger">
                                                <button
                                                    class=" col-lg-12 flex items-right text-sm font-medium text-white transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                                              
                                                        <div class="col-lg-8"></div>

                                                        <div class="col-lg-4">
                                                            İşlemler
                                                        </div>
                                                
                        
                                                    <div class="ml-1">
                                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </x-slot>
                        
                                            <x-slot name="content">
                                                <x-dropdown-link :href="route('halisaha.editpage', ['id' => $item->id])">
                                                    Düzenle
                                                </x-dropdown-link>
                                                <x-dropdown-link :href="route('halisaha.delete', ['id' => $item->id])">
                                                    Sil
                                                </x-dropdown-link>
                                                <!-- Authentication -->
                                              
                                            </x-slot>
                                        </x-dropdown>
                                         <h3 class="">{{ $item->name }}</h3>
                                       
                                
                                      
                                      
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
