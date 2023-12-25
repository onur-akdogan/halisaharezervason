 
@extends("layouts.app")
@section("main")
   
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
           
     
      
      
    <style>
        .fc-license-message {
            display: none;
        }.fc .fc-timegrid-slot{
            height: 3em !important;
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
 
         
 
   var calendarEl = document.getElementById('calendar1');
   var calendar = new FullCalendar.Calendar(calendarEl, {
     initialView: 'timeGridWeek',
     defaultTimedEventDuration: macSuresi,
     locale: 'tr', // Türkçe dilini tanımlama
     slotDuration: macSuresi,
     slotMinTime:acilisSaati,
 slotMaxTime: kapanisSaati,
 hiddenDays: offdays, // Tüm günleri gizle
 
 events: [
       {
         id: 1881,
         title: 'Örnek Etkinlik',
         start: '2023-12-23T14:00:00',
          allDay: false,
       }
     ],
     eventClick: function(info) {
       // Burada tıklanan etkinlik hakkında bilgileri işleyebilirsiniz
       alert('Etkinliğe Tıklandı: ' + info.event.title);
     },
     dateClick: function(info) {
       // Burada tıklanan tarih hakkında bilgileri işleyebilirsiniz
       alert('Tarihe Tıklandı: ' + info.dateStr);
       calendar.addEvent({
         title: 'Bu Saat Dolu',
         start: info.dateStr,
         color: 'red',
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
     color: 'red',
   });
 
 
   calendar.render();
 });
  
     </script>
   
 

<div class="col-lg-12">
    <div class="col-lg-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 backgrounds">
       
                    <div id='calendar1' class='calendar col-lg-12'></div>
               
            </div>
        </div>
    </div>
</div>
@endsection
 

