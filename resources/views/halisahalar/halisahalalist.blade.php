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
             background-color: #356000;

             margin: 0 auto;
             padding: 20px;
             border-radius: 10px;
             -moz-border-radius: 10px;
             -webkit-border-radius: 10px;
         }

         .fc .fc-toolbar-title {
             display: none !important;
         }

         .fc .fc-timegrid-body {
             min-height: 100px !important;

         }
     </style>
     <style>
         .swal2-select {
             background: white;
             color: #000 !important;
             min-width: 100%;
             padding: 0px;
             margin: 0px;


         }

         .fc .fc-scrollgrid-liquid {
             background: #356000;
         }

         .swal2-input,
         .swal2-file,
         .swal2-textarea {
             background: white;
             color: #000 !important;
             box-shadow: inset 0 1px 1px black;
         }

         .fc-direction-ltr .fc-timegrid-col-events {
             margin: 0px 0% 0px 0px !important;
         }
     </style>

     <script>
         const td = document.querySelector("td[class='fc-timegrid-slot fc-timegrid-slot-lane']");
         td.textContent = "Boş";
     </script>

     <script>
         try {
             document.addEventListener('DOMContentLoaded', function() {

                 const buttons = document.querySelectorAll(".nav-link");

                 function handleClick(event) {
                     const clickedButton = event.target;
                     const clickedId = clickedButton.id.split("-")[
                         1]; // Butonun ID'sinden halisaha ID'sini ayıklayın



                     const tabContent = document.querySelector("#pills-" + clickedId);
                     tabContent.classList.add("show", "active");
                     fetch('/apicalender/' + clickedId, {
                             method: 'GET', // GET isteği
                         })
                         .then(response => response.json()) // Yanıtı JSON olarak ayrıştırın
                         .then(data => {

                             // Yanıtı işleyin
                             // Gelen yanıtı değişkenlere ekleyin

                             acilisSaati = data.halisaha.starthour;
                             kapanisSaati = data.halisaha.endhour;
                             macSuresi = data.halisaha.macsuresi;
                             offdays = data.halisaha.offdays;
                             events = data.events;
                             console.log(data.halisaha);
                             sahaid = data.halisaha.id;

                             function toplamDakika(zaman) {
                                 var zamanParcalari = zaman.split(":");
                                 var saat = parseInt(zamanParcalari[0]);
                                 var dakika = parseInt(zamanParcalari[1]);
                                 return saat * 60 + dakika;
                             }
                             var rezervasyonAraligi = data.halisaha.macsuresi;

                             // Açılış ve kapanış saatlerini dakika cinsine dönüştürüyoruz
                             var acilisDakika = toplamDakika(acilisSaati);
                             var kapanisDakika = toplamDakika(kapanisSaati);

                             // Rezervasyon aralığını dakika cinsine dönüştürüyoruz
                             var rezervasyonAraligiDakika = toplamDakika(rezervasyonAraligi);

                             // Aralığı hesaplıyoruz
                             var aralik = (kapanisDakika - acilisDakika) / rezervasyonAraligiDakika;

                             function toplamDakika(zaman) {
                                 var zamanParcalari = zaman.split(":");
                                 var saat = parseInt(zamanParcalari[0]);
                                 var dakika = parseInt(zamanParcalari[1]);
                                 return saat * 60 + dakika;
                             }
                             var element = document.querySelector('[aria-labelledby="fc-dom-1"]');

                             // Yüksekliği ayarlıyoruz
                             var height = aralik * 100; // Aralık sayısını yükseklik olarak çarpıyoruz
                             element.style.height = height + 'px !important'; // Yüksekliği ayarlıyoruz

                             console.log(aralik);

                             calendarEl = document.getElementById('calendar' + clickedId);

                             calendar = new FullCalendar.Calendar(calendarEl, {
                                 initialView: 'timeGridWeek',
                                 defaultTimedEventDuration: macSuresi,
                                 locale: 'tr', // Türkçe dilini tanımlama
                                 slotDuration: macSuresi,
                                 slotMinTime: data.halisaha.starthour,
                                 slotMaxTime: kapanisSaati,
                                 hiddenDays: offdays, // Tüm günleri gizle

                                 events: events,

                                 eventClick: function(info) {
                                     console.log(info.event);
                                     Swal.fire({
                                         background: 'white',

                                         title: "<h3 style='color:black'>İsim: " + info.event
                                             .extendedProps
                                             .userName + "</h3>",

                                         html: "<h5 style='color:black'> İletişim:" + info
                                             .event
                                             .extendedProps
                                             .userinfo + "</h5>" +
                                             "<h5 style='color:black'> Not:" + info.event
                                             .extendedProps.note + "</h5>",
                                         icon: "info",

                                         showDenyButton: true,
                                         showCancelButton: false,
                                         confirmButtonText: "İptal et",
                                         denyButtonText: `Vazgeç`
                                     }).then((result) => {
                                         /* Read more about isConfirmed, isDenied below */
                                         if (result.isConfirmed) {
                                             Swal.fire({
                                                 background: 'white',
                                                 title: "<h3 style='color:black'> " +
                                                     "İptal etmek istediğinize eminmisiniz?" +
                                                     "</h3>",

                                                 showDenyButton: true,
                                                 showCancelButton: false,
                                                 confirmButtonText: "İptal et",
                                                 denyButtonText: `Vazgeç`

                                             }).then((result) => {
                                                 /* Read more about isConfirmed, isDenied below */
                                                 if (result.isConfirmed) {
                                                     var url =
                                                         "{{ route('calender.delete', ['id' => ':id']) }}"
                                                         .replace(
                                                             ':id', info.event.id);
                                                     window.open(url, '_self');

                                                 } else if (result.isDenied) {

                                                 }
                                             });
                                         } else if (result.isDenied) {

                                         }
                                     });



                                     // Burada tıklanan etkinlik hakkında bilgileri işleyebilirsiniz



                                 },
                                 dateClick: function(info) {

                                     const tarih = new Date(info.dateStr);

                                     if (tarih.getTime() < Date.now()) {
                                         Swal.fire({
                                             background: 'white',
                                             title: "<h3 style='color:black'>Geçmiş Zaman'a Ekleme Yapılmaz</h3>",

                                         })
                                     } else {

                                         Swal.fire({
                                                 background: 'white',
                                                 title: "<h3 style='color:black'>Bu saat aralığına eklemek istediğinize emin misiniz?</h3>",
                                                 showDenyButton: true,
                                                 input: "select",
                                                 inputOptions: {
                                                     0: "Abone Değil",
                                                     4: "1 Ay",
                                                     12: "3 Ay",
                                                     24: "6 Ay",
                                                     48: "12 Ay",
                                                     96: "24 Ay",
                                                     144: "36 Ay"


                                                 },


                                                 html: `
                                                 <div class="row">
                                            <input type="text" name="userName" class="swal2-input" placeholder="İrtibat İsmi">
                                            <input type="text" name="userinfo" class="swal2-input" placeholder="İrtibat Bilgileri (Telefon no)">
                                                   </div>
                                                    <input type="text" name="note" class="swal2-input" placeholder="Not">
                                                                    `,
                                                 showCancelButton: false,
                                                 confirmButtonText: "Ekle",
                                                 denyButtonText: `Vazgeç`
                                             })
                                             .then((result) => {

                                                 if (result.isConfirmed) {
                                                     const abonelikSuresi = result.value;


                                                     const userName = $("input[name='userName']")
                                                         .val();
                                                     const userinfo = $("input[name='userinfo']")
                                                         .val();
                                                     const note = $("input[name='note']").val();
                                                     const aboneTime = $(
                                                             "input[name='aboneTime']")
                                                         .val();


                                                     const dateStr = info.dateStr;


                                                     $.ajax({
                                                         type: "POST",
                                                         url: "{{ route('calender.add') }}",
                                                         data: {
                                                             title: abonelikSuresi,
                                                             sahaId: sahaid,
                                                             date: dateStr,
                                                             userName: userName,
                                                             userinfo: userinfo,
                                                             note: note,
                                                             aboneTime: abonelikSuresi,
                                                             _token: '{{ csrf_token() }}'
                                                         },
                                                         success: function(response) {
                                                             // Başarılı cevap
                                                             calendar.addEvent({
                                                                 title: 'Dolu',
                                                                 start: dateStr,
                                                                 color: 'blue',
                                                             });
                                                             setInterval(() => window
                                                                 .location
                                                                 .reload(
                                                                     false),
                                                                 1000);

                                                             Swal.fire({
                                                                 background: 'white',

                                                                 position: "top-end",
                                                                 icon: "success",
                                                                 title: "Eklendi",
                                                                 showConfirmButton: false,
                                                                 timer: 1500
                                                             });
                                                         },
                                                         error: function(error) {
                                                             // Hata durumu
                                                             console.error(error);
                                                             Swal.fire({
                                                                 background: 'white',

                                                                 icon: 'error',
                                                                 title: 'Hata',
                                                                 text: error
                                                             });
                                                         }
                                                     });
                                                 } else if (result.isDenied) {

                                                 }
                                             });
                                     }

                                 },
                                 visibleRange: {
                                     start: '2023-12-23T12:00:00',
                                     end: '2023-12-23T18:00:00',
                                 },
                             });


                             calendar.render();
                             console.log(calendarEl);

                         })
                         .catch(error => {
                             // Hataları yakalayın
                             console.error('Hata:', error);
                         });




                 }

                 buttons.forEach(button => {
                     button.addEventListener("click", handleClick);
                 });
             });

         } catch (error) {
             console.error('hataaaa:', error);

         }
     </script>

     <style>
         .fc-license-message {
             display: none;
         }

         .fc .fc-timegrid-slot {}

         .fc-theme-standard td,
         .fc-theme-standard th {
             border: 4px solid rgb(255, 255, 255);

         }

         .fc .fc-timegrid-slot-minor {
             border: 4px solid white;
             padding: 2px !important;
             margin: 2px !important;

         }

         .fc .fc-timegrid-axis-frame {
             display: none !important;
             padding: 2px !important;
             margin: 2px !important;


         }

         .fc,
         .fc *,
         .fc ::after,
         .fc ::before {
             color: white;
         }

         .fc .fc-timegrid .fc-daygrid-body {
             display: none !important;
         }
     </style>
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/index.global.min.js'></script>
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/main.min.css' />
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/main.min.js'></script>
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/locales/tr.min.js'></script>

     <script>
         try {
             document.addEventListener('DOMContentLoaded', function() {

                 var clickedId = {!! json_encode($id) !!};



                 fetch('/apicalender/' + clickedId, {
                         method: 'GET', // GET isteği
                     })
                     .then(response => response.json()) // Yanıtı JSON olarak ayrıştırın
                     .then(data => {
                         // Yanıtı işleyin
                         // Gelen yanıtı değişkenlere ekleyin

                         acilisSaati = data.halisaha.starthour;
                         kapanisSaati = data.halisaha.endhour;
                         macSuresi = data.halisaha.macsuresi;
                         offdays = data.halisaha.offdays;
                         events = data.events;
                         sahaid = data.halisaha.id;

                         calendarEl = document.getElementById('calendar' + clickedId);

                         calendar = new FullCalendar.Calendar(calendarEl, {
                             initialView: 'timeGridWeek',
                             defaultTimedEventDuration: macSuresi,
                             locale: 'tr', // Türkçe dilini tanımlama
                             slotDuration: macSuresi,
                             slotMinTime: data.halisaha.starthour,
                             slotMaxTime: kapanisSaati,
                             hiddenDays: offdays, // Tüm günleri gizle


                             events: events,
                             eventClick: function(info) {
                                 console.log(info.event);
                                 Swal.fire({
                                     background: 'white',

                                     title: "<h3 style='color:black'>İsim: " + info.event
                                         .extendedProps
                                         .userName + "</h3>",

                                     html: "<h5 style='color:black'> İletişim:" + info
                                         .event
                                         .extendedProps
                                         .userinfo + "</h5>" +
                                         "<h5 style='color:black'> Not:" + info.event
                                         .extendedProps.note + "</h5>",
                                     icon: "info",

                                     showDenyButton: true,
                                     showCancelButton: true,
                                     confirmButtonText: "İptal et",
                                     denyButtonText: `Düzenle`

                                 }).then((result) => {
                                     /* Read more about isConfirmed, isDenied below */
                                     if (result.isConfirmed) {
                                         Swal.fire({
                                             background: 'white',
                                             title: "<h3 style='color:black'> " +
                                                 "İptal etmek istediğinize eminmisiniz?" +
                                                 "</h3>",

                                             showDenyButton: true,
                                             showCancelButton: false,
                                             confirmButtonText: "İptal et",
                                             denyButtonText: `Vazgeç`

                                         }).then((result) => {
                                             /* Read more about isConfirmed, isDenied below */
                                             if (result.isConfirmed) {
                                                 var url =
                                                     "{{ route('calender.delete', ['id' => ':id']) }}"
                                                     .replace(
                                                         ':id', info.event.id);
                                                 window.open(url, '_self');

                                             } else if (result.isDenied) {

                                             }
                                         });
                                     } else if (result.isDenied) {
                                         Swal.fire({
                                             background: 'white',
                                             title: "<h3 style='color:black'>" +
                                                 "Düzenle" +
                                                 "</h3>",

                                             showDenyButton: true,
                                             html: `
                           <div class="row">
                               <input type="text" name="userName" class="swal2-input" placeholder="İrtibat İsmi" value=` +
                                                 info.event
                                                 .extendedProps.userName + `>
                               <input type="text" name="userinfo" class="swal2-input"  value= ` + info.event
                                                 .extendedProps.userinfo + ` placeholder="İrtibat Bilgileri (Telefon no)"</div>
                           <input type="text" name="note" class="swal2-input" placeholder="Not"  value=` + info.event
                                                 .extendedProps.note + `>
                               `,
                                             showCancelButton: false,
                                             confirmButtonText: "Düzenle",
                                             denyButtonText: `Vazgeç`
                                         }).then((result) => {
                                             if (result.isConfirmed) {
                                                 const userName = $(
                                                         "input[name='userName']")
                                                     .val();
                                                 const userinfo = $(
                                                         "input[name='userinfo']")
                                                     .val();
                                                 const note = $("input[name='note']")
                                                     .val();


                                                 const dateStr = info.dateStr;


                                                 $.ajax({
                                                     type: "POST",
                                                     url: "{{ route('calender.update') }}",
                                                     data: {
                                                         title: 1,

                                                         id: info.event.id,

                                                         sahaId: sahaid,
                                                         userName: userName,
                                                         userinfo: userinfo,
                                                         note: note,

                                                         _token: '{{ csrf_token() }}'
                                                     },
                                                     success: function(
                                                         response) {

                                                         setInterval(
                                                             () =>
                                                             window
                                                             .location
                                                             .reload(
                                                                 false
                                                             ),
                                                             1000);

                                                         Swal.fire({
                                                             background: 'white',

                                                             position: "top-end",
                                                             icon: "success",
                                                             title: "Güncellendi",
                                                             showConfirmButton: false,
                                                             timer: 1500
                                                         });
                                                     },
                                                     error: function(error) {
                                                         // Hata durumu
                                                         console.error(
                                                             error);
                                                         Swal.fire({
                                                             background: 'white',

                                                             icon: 'error',
                                                             title: 'Hata',
                                                             text: error
                                                         });
                                                     }
                                                 });
                                             } else if (result.isDenied) {
                                                 // Vazgeçildiğinde yapılacak işlem
                                             }
                                         });
                                     }
                                 });



                                 // Burada tıklanan etkinlik hakkında bilgileri işleyebilirsiniz



                             },
                             dateClick: function(info) {
                                 const tarih = new Date(info.dateStr);

                                 if (tarih.getTime() < Date.now()) {
                                     Swal.fire({
                                         background: 'white',
                                         title: "<h3 style='color:black'>Geçmiş Zaman'a Ekleme Yapılmaz</h3>",

                                     })
                                 } else {
                                     Swal.fire({
                                         background: 'white',
                                         title: "<h3 style='color:black'> " +
                                             "Bu saat aralığına eklemek istediğinize emin misiniz?" +
                                             "</h3>",
                                         input: "select",
                                         inputOptions: {
                                             0: "Abone Değil",
                                             4: "1 Ay",
                                             12: "3 Ay",
                                             24: "6 Ay",
                                             48: "12 Ay",
                                             96: "24 Ay",
                                             144: "36 Ay"


                                         },



                                         showDenyButton: true,
                                         html: `
                                                       <div class="row">
                                                           <input type="text" name="userName" class="swal2-input" placeholder="İrtibat İsmi">
                                                           <input type="text" name="userinfo" class="swal2-input" placeholder="İrtibat Bilgileri (Telefon no)"</div>
                                                       <input type="text" name="note" class="swal2-input" placeholder="Not">
                                                    
                                                    `,
                                         showCancelButton: false,
                                         confirmButtonText: "Ekle",
                                         denyButtonText: `Vazgeç`
                                     }).then((result) => {
                                         if (result.isConfirmed) {
                                             const abonelikSuresi = result.value;

                                             const userName = $("input[name='userName']")
                                                 .val();
                                             const userinfo = $("input[name='userinfo']")
                                                 .val();
                                             const note = $("input[name='note']").val();

                                             const dateStr = info.dateStr;


                                             $.ajax({
                                                 type: "POST",
                                                 url: "{{ route('calender.add') }}",
                                                 data: {
                                                     title: abonelikSuresi,
                                                     sahaId: sahaid,
                                                     date: dateStr,
                                                     userName: userName,
                                                     userinfo: userinfo,
                                                     note: note,
                                                     aboneTime: abonelikSuresi,
                                                     _token: '{{ csrf_token() }}'
                                                 },
                                                 success: function(response) {
                                                     // Başarılı cevap
                                                     calendar.addEvent({
                                                         title: 'Dolu',
                                                         start: dateStr,
                                                         color: 'blue',
                                                     });
                                                     setInterval(() => window
                                                         .location.reload(
                                                             false),
                                                         1000);

                                                     Swal.fire({
                                                         background: 'white',

                                                         position: "top-end",
                                                         icon: "success",
                                                         title: "Eklendi",
                                                         showConfirmButton: false,
                                                         timer: 1500
                                                     });
                                                 },
                                                 error: function(error) {
                                                     // Hata durumu
                                                     console.error(error);
                                                     Swal.fire({
                                                         background: 'white',

                                                         icon: 'error',
                                                         title: 'Hata',
                                                         text: error
                                                     });
                                                 }
                                             });
                                         } else if (result.isDenied) {
                                             // Vazgeçildiğinde yapılacak işlem
                                         }
                                     });
                                 }



                             },
                             visibleRange: {
                                 start: '2023-12-23T12:00:00',
                                 end: '2023-12-23T18:00:00',
                             },
                         });



                         calendar.render();
                         console.log(calendarEl);

                     })
                     .catch(error => {
                         // Hataları yakalayın
                         console.error('Hata:', error);
                     });







             });

         } catch (error) {
             console.error('hataaaa:', error);

         }
     </script>





     <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="height: 30%">
         <div class="p-3 text-gray-900 dark:text-gray-100 backgrounds">



             <h3 style="color: #356000">
                 Sahalarım
             </h3>
             <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                 @foreach ($halisaha as $key => $item)
                     <li class="nav-item" role="presentation">
                         <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="pills-{{ $item->id }}-tab"
                             data-bs-toggle="pill" data-bs-target="#pills-{{ $item->id }}" type="button"
                             role="tab" aria-controls="pills-{{ $item->id }}" aria-selected="true">
                             {{ $item->name }}
                         </button>
                     </li>
                 @endforeach
             </ul>

             <div class="tab-content" id="pills-tabContent">
                 @foreach ($halisaha as $key => $item)
                     <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="pills-{{ $item->id }}"
                         role="tabpanel" aria-labelledby="pills-{{ $item->id }}-tab">

                         <div id='calendar{{ $item->id }}' class='calendar col-lg-12' style="height: 50%">
                         </div>



                     </div>
                 @endforeach
             </div>



         </div>
     </div>
 @endsection
