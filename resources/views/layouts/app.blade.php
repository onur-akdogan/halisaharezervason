<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>.select_checkbox .btn {
            border-radius: 3px;
            padding: 12px 10px;
            box-shadow: none;
            border: 1px solid #d9d9d9;
            outline: 0;
            color: #333 !important;
          }
          a:hover{
            color: #0d6126 !important;
          }
           .border-indigo-400{
            border-color: #0d6126 !important;
 
           }
          .customcolor :hover{
            
            color: #0d3461 !important;
          }
          --bs-blue: #0d6126;
          .select_checkbox .bootstrap-select .dropdown-toggle:focus,
          .select_checkbox
            .bootstrap-select
            > select.mobile-device:focus
            + .dropdown-toggle {
            outline: 0 !important;
          }
          </style>
          <script>$(document).ready(function () {
            $(".selectpicker").selectpicker();
          
            $(".selectpicker").on(
              "changed.bs.select",
              function (e, clickedIndex, isSelected, previousValue) {
                var selectedCount = $(".selectpicker option:selected").length;
                if (selectedCount > 4) {
                  $(".filter-option-inner-inner").text(selectedCount + " selected");
                } else {
                  $(".filter-option-inner-inner").text(
                    $(".selectpicker").val().join(", ")
                  );
                }
              }
            );
          });
          </script>
          
<style>
  .bg-dark{
        background-color: 05291c !important;
    }
     .py-6{
        background-color: 05291c !important;
    }        
     .py-12{
        background-color: 05291c !important;
    }
    .calendar {
        background-color: 05291c !important;
    }
    .backgrounds{
        background-color: 05291c !important;

    }
</style>
        <title>{{ config('app.name', 'Laravel') }}</title>



        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="resources/css/app.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta3/js/bootstrap.min.js" integrity="sha512-mp3VeMpuFKbgxm/XMUU4QQUcJX4AZfV5esgX72JQr7H7zWusV6lLP1S78wZnX2z9dwvywil1VHkHZAqfGOW7Nw==" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta3/css/bootstrap.min.css" integrity="sha512-N415hCJJdJx+1UBfULt+i+ihvOn42V/kOjOpp1UTh4CZ70Hx5bDlKryWaqEKfY/8EYOu/C2MuyaluJryK1Lb5Q==" crossorigin="anonymous" />
         <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

         <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
         
        <!-- Scripts
        @ vite(['resources/css/app.css', 'resources/js/app.js']) -->
      
    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')
 
            @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: '{{ session('success') }}',
                    background: 'white',

                });
            </script>
        @endif
            <!-- Page Heading -->
            

            <!-- Page Content -->
            <main>
               @yield("main")
            </main>
        </div>
    </body>
</html>
