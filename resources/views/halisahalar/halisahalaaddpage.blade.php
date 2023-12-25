 @extends('layouts.app')
 @section('main')
     <!-- Bootstrap CSS CDN -->

     <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
     <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
     <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
     

     <div class="py-12">
         <div class="lg:px-8">
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900 dark:text-gray-100 backgrounds">




                     <div class="container">
                        <form action="{{route('halisaha.add')}}" method="POST">
                            @csrf
                            <div class="form-group row">
                              <label for="inputEmail3" class="col-sm-2 col-form-label">Halısaha İsmi</label>
                              <div class="col-sm-10">
                                <input type="text"  name="name" class="form-control" id="inputEmail3" placeholder="Halısaha ismi">
                              </div>
                            </div>
                            <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Maç Süresi (dk)</label>
                                <div class="col-sm-10">
                                  <input
                                  type="number"  name="macsuresi" 
                                   class="form-control" id="inputEmail3" placeholder="Rezarvasyon süresi">
                                </div>
                              </div>
                              <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Saha Açılış Saati</label>
                                <div class="col-sm-10">
                                    <div class="cs-form">
                                        <input type="time" name="starthour"  class="form-control" value="10:05 AM" />
                                      </div>
                                    
                                    </div>
                              </div>
                             
                              <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Saha Kapanış Saati</label>
                                <div class="col-sm-10">
                                    <div class="cs-form">
                                        <input type="time" name="endhour"  class="form-control" value="10:05 AM" />
                                      </div>
                                    
                                    </div>
                              </div>
                              offdays
                
                    <select id="example-getting-started" multiple="multiple">
    <option value="cheese">Cheese</option>
    <option value="tomatoes">Tomatoes</option>
    <option value="mozarella">Mozzarella</option>
    <option value="mushrooms">Mushrooms</option>
    <option value="pepperoni">Pepperoni</option>
    <option value="onions">Onions</option>
</select>

                            <div class="form-group row">
                              <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Ekle</button>
                              </div>
                            </div>
                          </form>
                     </div>

                 </div>
             </div>
         </div>
     </div>
     <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').datetimepicker();
        });
    </script>

 @endsection
