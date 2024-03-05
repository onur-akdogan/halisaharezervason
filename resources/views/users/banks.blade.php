@extends('layouts.app')
@section('main')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <!-- Bootstrap CSS CDN -->
    <script>
        $(document).ready(function() {

            var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
                removeItemButton: true,
                maxItemCount: 7,
                searchResultLimit: 7,
                renderChoiceLimit: 7
            });


        });
    </script>
    <div class="py-12">
        
        <div class="lg:px-8">
    

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            
                <div class="p-6 text-gray-900 dark:text-gray-100 backgrounds">
                <div class="col-lg-12">
                   <div class="row"> <div class="col-lg-10"></div>
                   <div class="col-lg-2">
                       <a class="btn btn-danger" href="{{route('admin.banksaddpage')}}">Banka Ekle</a> 
                   </div></div>
                </div>



                    <div class="container">
                        <table class="table table-striped">
                            <thead>
                              <tr>
                        
                                <th scope="col">Banka Adı</th>
                                <th scope="col">Kullanıcı Adı</th>
                                <th scope="col">Iban</th>
                                <th scope="col">İletişim Numarası</th>

                                 <th scope="col">Sil</th>

                             
                             
                              </tr>
                            </thead>
                            <tbody> 
                                @foreach ($banks as $item)
                              <tr>
                         
                                <td>         {{$item->bankname}}</td>
                                <td>  {{$item->username}}</td>
                                <td>  {{$item->iban}}</td>
                                <td>  {{$item->iletisim}}</td>

                                 <td>   <a class="btn btn-danger" href="{{route("admin.banksdelete",$item->id)}}">Sil</a></td>
                            

                                
                               
                              </tr>
                           
                    @endforeach
                            </tbody>
                          </table>
                 
               
                      
              

                        
                       

                        
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $('#datetimepicker1').datetimepicker();
        });
    </script>
@endsection
