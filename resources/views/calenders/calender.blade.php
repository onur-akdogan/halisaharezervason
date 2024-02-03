 @extends('layouts.app')
 @section('main')
     <style>
         .calendar .col-lg-12 .fc .fc-media-screen .fc-direction-ltr .fc-theme-standard {}

         .col-lg-12 {
             height: 100% !important;

         }
     </style>
     <script>
        
         function openCity(evt, cityName) {
             // Declare all variables
             var i, tabcontent, tablinks;

             // Get all elements with class="tabcontent" and hide them
             tabcontent = document.getElementsByClassName("tabcontent");
             for (i = 0; i < tabcontent.length; i++) {
                 tabcontent[i].style.display = "none";
             }

             // Get all elements with class="tablinks" and remove the class "active"
             tablinks = document.getElementsByClassName("tablinks");
             for (i = 0; i < tablinks.length; i++) {
                 tablinks[i].className = tablinks[i].className.replace(" active", "");
             }

             // Show the current tab, and add an "active" class to the button that opened the tab
             document.getElementById(cityName).style.display = "block";
             evt.currentTarget.className += " active";
         }
     </script>
<script>
const td = document.querySelector("td[class='fc-timegrid-slot fc-timegrid-slot-lane']");
td.textContent = "Boş";
</script>



     <style>
         .fc-license-message {
             display: none;
         }

         .fc .fc-timegrid-slot {
            
         }
         
         .fc-theme-standard td, .fc-theme-standard th{
             border:4px solid gray;
             
         }
         .fc .fc-timegrid-slot-minor{
            border:4px solid gray;
            padding: 2px !important;
            margin: 2px !important;

         }
         .fc .fc-timegrid-axis-frame {
            display:none !important;
            padding: 2px !important;
            margin: 2px !important;


         }
         .fc .fc-timegrid .fc-daygrid-body{
            display:none !important;
         }
         </style>
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/index.global.min.js'></script>
     <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/main.min.css' />
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/main.min.js'></script>
     <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.10/locales/tr.min.js'></script>
     <script>
         document.addEventListener('DOMContentLoaded', function() {

             var acilisSaati = {!! json_encode($acilissaati) !!};
             var kapanisSaati = {!! json_encode($kapanissaati) !!};
             var macSuresi = {!! json_encode($macsuresi) !!};
             var offdays = {!! json_encode($offdays) !!};
             var events = {!! json_encode($events) !!};


             var calendarEl = document.getElementById('calendar1');
             var calendar = new FullCalendar.Calendar(calendarEl, {
                 initialView: 'timeGridWeek',
                 defaultTimedEventDuration: macSuresi,
                 locale: 'tr', // Türkçe dilini tanımlama
                 slotDuration: macSuresi,
                 slotMinTime: acilisSaati,
                 slotMaxTime: kapanisSaati,
                 hiddenDays: offdays, // Tüm günleri gizle

                 events: events,
                 eventClick: function(info) {
                     console.log(info.event);
                     Swal.fire({
                         background: 'white',

                         title: "<h3 style='color:black'>İsim: " + info.event.extendedProps
                             .userName + "</h3>",

                         html: "<h5 style='color:black'> İletişim:" + info.event.extendedProps
                             .userinfo + "</h5>" + "<h5 style='color:black'> Not:" + info.event
                             .extendedProps.note + "</h5>",
                         icon: "info",

                         showDenyButton: true,
                         showCancelButton: false,
                         confirmButtonText: "Sil",
                         denyButtonText: `Vazgeç`
                     }).then((result) => {
                         /* Read more about isConfirmed, isDenied below */
                         if (result.isConfirmed) {
                             Swal.fire({
                                 background: 'white',
                                 title: "<h3 style='color:black'> " +
                                     "Silmek istediğinize eminmisiniz?" + "</h3>",

                                 showDenyButton: true,
                                 showCancelButton: false,
                                 confirmButtonText: "Sil",
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

                     Swal.fire({
                         background: 'white',
                         title: "<h3 style='color:black'>İsim: " +
                             "Bu saat aralığına eklemek istediğinize emin misiniz?" + "</h3>",

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
                             const userName = $("input[name='userName']").val();
                             const userinfo = $("input[name='userinfo']").val();
                             const note = $("input[name='note']").val();


                             const dateStr = info.dateStr;


                             $.ajax({
                                 type: "POST",
                                 url: "{{ route('calender.add') }}",
                                 data: {
                                     title: 'Dolu',
                                     sahaId: '{{ $id }}',
                                     date: dateStr,
                                     userName: userName,
                                     userinfo: userinfo,
                                     note: note,

                                     _token: '{{ csrf_token() }}'
                                 },
                                 success: function(response) {
                                     // Başarılı cevap
                                     calendar.addEvent({
                                         title: 'Dolu',
                                         start: dateStr,
                                         color: 'blue',
                                     });
                                     setInterval(() => window.location.reload(false),
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












                 },
                 visibleRange: {
                     start: '2023-12-23T12:00:00',
                     end: '2023-12-23T18:00:00',
                 },
             });

             calendar.addEvent({
                 title: 'Renkli Etkinlik',
                 start: '2023-12-23T14:40:00',
                 color: 'green',
             });


             calendar.render();
         });
     </script>
                 <div id='calendar1' class='calendar col-lg-12'></div>




    
  @endsection
