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
                        <form action="{{ route('halisaha.update',) }}" method="POST">
                            @csrf
                            <div class="form-group row">

                                <label for="inputEmail3" class="col-sm-2 col-form-label">Halısaha İsmi</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="inputEmail3"
                                        placeholder="Halısaha ismi" value="{{$halisahadata->name}}">
                                </div>
                            </div>
                            <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Maç Süresi (dk)</label>
                                <div class="col-sm-10">
                                    <input type="number" name="macsuresi" class="form-control" id="inputEmail3"
                                        placeholder="Rezarvasyon süresi"  value="{{$macsuresi}}">
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$halisahadata->id}}">
                            <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Saha Açılış Saati</label>
                                <div class="col-sm-10">
                                    <div class="cs-form">
                                        <input type="time" name="starthour" class="form-control"   value="{{$halisahadata->starthour}}" />
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Saha Kapanış Saati</label>
                                <div class="col-sm-10">
                                    <div class="cs-form">
                                        <input type="time" name="endhour" class="form-control"   value="{{$halisahadata->endhour}}"/>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row pt-2 bb-5">
                                <div class="row d-flex justify-content-center mt-100">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kapalı Günler</label>

                                    <div class="col-sm-10">
                                        <select id="choices-multiple-remove-button" placeholder="Kapalı Olan Günler"
                                            multiple name="offday[]">
                                            @foreach ($days as $day) 
                                            <option value="{{$day['id']}}" @if(in_array($day['id'], $selectedDays)) selected @endif>
                                              {{$day['name']}}
                                          </option>
                              
                                     @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
<div class="col-lg-12" style="height: 200px"></div>
<div class="form-group row pt-5">
    <div class="col-sm-11">
        <!-- Boş bir sütun, butonu en sağa yaslamak için -->
    </div>
    <div class="col-sm-1">
        <button type="submit" class="btn btn-primary">Düzenle</button>
    </div>
   
</div>
                        </form>
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
