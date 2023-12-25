 @extends('layouts.app')
 @section('main')
     <!-- Bootstrap CSS CDN -->
 

     <div class="py-12">
         <div class="lg:px-8">
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100 backgrounds">




                     <div class="container">
                         <div class="row">
                             @foreach ($halisaha as $item)
                             <div class="card text-white bg-dark mb-3 m-3" style="max-width: 18rem;">
                              <div class="card-header">{{ $item->name }}</div>
                              <div class="card-body">
                                 <p class="card-text">     
                                  <a href="{{ route('calender.index', ['id' => $item->id]) }}"
                                      class="btn btn-primary">Maç Ekle</a>
                                  <a href="{{ route('calender.index', ['id' => $item->id]) }}"
                                      class="btn btn-info">Düzenle</a>
                                  <a href="{{ route('calender.index', ['id' => $item->id]) }}"
                                      class="btn btn-danger">Sil</a></p>
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
