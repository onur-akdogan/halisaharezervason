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
                        <form action="{{ route('admin.banksadd') }}" method="POST">
                            @csrf
                            <div class="form-group row">

                                <label for="inputEmail3" class="col-sm-2 col-form-label">Banka İsmi</label>
                                <div class="col-sm-10">
                                    <input type="text" name="bankname" class="form-control" id="inputEmail3"
                                        placeholder="Banka İsmi">
                                </div>
                            </div>
                            <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Kullanıcı Adı</label>
                                <div class="col-sm-10">
                                    <input type="text" name="username" class="form-control" id="inputEmail3"
                                        placeholder='Kullanıcı Adı ("Banka kullanıcı İsmi")'  >
                                </div>
                            </div>
                            <div class="form-group row pt-2">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">IBAN Numarası</label>
                                <div class="col-sm-10">
                                    <input type="text" name="iban" class="form-control" id="inputEmail3"
                                        placeholder="IBAN Numarası"  >
                                </div>
                            </div>
 
                            <div class="col-lg-12" style="height: 200px"></div>
                            <div class="form-group row pt-5">
                                <div class="col-sm-11">
                                    <!-- Boş bir sütun, butonu en sağa yaslamak için -->
                                </div>
                                <div class="col-sm-1">
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
        $(function() {
            $('#datetimepicker1').datetimepicker();
        });
    </script>
@endsection
