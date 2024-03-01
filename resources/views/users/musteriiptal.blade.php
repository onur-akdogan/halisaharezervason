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




                    <div class="container">
                        <table class="table table-striped">
                            <thead>
                              <tr>
                        
                                <th scope="col">İsim</th>
                                <th scope="col">İletişim</th>
                                <th scope="col">Not</th>

                                <th scope="col">Rezervasyon Tarihi</th>
                                <th scope="col"></th>

                                
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                              <tr>
                         
                                <td>         {{$item->userName}}</td>
                                <td>  {{$item->userinfo}}</td>
                                <td>  {{$item->note}}</td>

                                <td> {{date('Y-m-d H:i:s', strtotime( $item->date))
                                
                                
                               }}</td>
                                    <td><a class="btn btn-success" href="{{route('calender.deleteback',$item->id)}}">Geri Çek</a> </td>
                               
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
