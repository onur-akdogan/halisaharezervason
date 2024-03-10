@extends('layouts.app')


@section('main')
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            /* Beyaz */
            border-top: 16px solid #3498db;
            /* Mavi */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            display: none;
            /* Başlangıçta gizli olacak */
        }

        .swal2-input,
        .swal2-file,
        .swal2-textarea {
            background-color: #ccc;
            color: rgb(0, 0, 0);

        }

        .swal2-input::placeholder {
            color: rgb(0, 0, 0);
            opacity: 1;
            /* Firefox */
        }

        .swal2-input::-ms-input-placeholder {
            /* Edge 12 -18 */
            color: rgb(0, 0, 0);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #f09f22;
        }

        table {
            width: 100%;
            border-collapse: collapse;

        }

        th {
            background-color: #fff !important;
        }

        #bos {
            max-height: 20px !important;
            background-color: #d9d9d9;
            color: #000000;
            margin: 0px !important;
            padding: 0px !important;
            border-radius: 12px;
            font-size: 12px;

        }

        #timeRangeString {
            height: 10px;
            margin: 0%;
            padding: 0%
        }

        #dolu {
            max-height: 20px !important;
            background-color: #207c35;
            color: white;
            margin: 0px !important;
            padding: 0px !important;
            border-radius: 12px;
            font-size: 12px;

        }

        #abone {
            max-height: 20px !important;
            background-color: #bd0101;
            color: white;
            margin: 0px !important;
            padding: 0px !important;
            border-radius: 12px;
            font-size: 12px;

        }

        tbody,

        tfoot,
        th,
        thead,
        tr {
            max-width: 2%;
        }

        td {
            border-radius: 8px;
            border: 4px solid #ffffff;
            padding: 0px;
            margin: 0px;
            text-align: center;
            color: white;
            height: 10px !important;
        }

        #clock-macsuresi {
            border: 1px solid #ccc;
            text-align: center;
            background-color: white;
            color: #3f2e5a;
            max-height: 5px !important;
            max-width: 55px;
            min-width: 50px;
            font-size: 12px;


            margin: 0px !important;
            padding: 5px !important;
            font-size: 12px;
            font-weight: 800;

        }

        #dayname {
            border: 1px solid #ccc;
            padding: 1px;
            text-align: center;
            background-color: white;
            color: #3f2e5a;
            min-width: 10%;
            font-size: 14px;
            font-weight: bold;

        }

        #boxs {
            height: 10px;
        }

        #next {
            width: 85px;
            height: 40px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        label {
            color: red;
            font-size: 12px;
        }

        #prev {
            width: 85px;
            height: 40px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;

        }

        #now {
            width: 85px;
            height: 40px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;

        }



        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function showDataBs(element) {
            element.innerText = element.getAttribute('data-bs'); // data-bs içeriğini göster
        }

        function hideDataBs(element) {
            element.innerText = element.getAttribute('data-original-content'); // Özgün içeriği geri yükle
        }
    </script>
    <script>
        sahaninidsi = 0;
       
        try {
            document.addEventListener('DOMContentLoaded', function() {
                const dataContainer = document.getElementById('pills-tabContent');
                const loader = document.getElementById('loader'); // Yükleme göstergesi
                const buttons = document.querySelectorAll(".nav-link");
                let htmlContent = ''; // HTML içeriğini oluşturmak için boş bir string

                function fetchData(clickedId, weeks) {
                    weeks;
                    sahaninidsi = clickedId;
                    loader.style.display = 'block'; // Yükleme göstergesini göster

                    fetch('/apicalender/' + clickedId + '/' + weeks, {
                            method: 'GET',
                            cache: 'no-cache' // Tarayıcı önbelleğini devre dışı bırakır
                        })
                        .then(response => response.json())
                        .then(data => {
                            allbox = 0;
                         allbox=(
                                    data.filteredDays.length)*data.reservationTimes.length;

                            loader.style.display = 'none'; // Yükleme göstergesini gizle

                            htmlContent = ''; // HTML içeriğini sıfırla

                            htmlContent += `<div class="tab-content" id="pills-tabContent"`;

                            data.allsaha.forEach(saha => {
                                let index = 0;
                                htmlContent += `
                           
                            <div class="tab-pane fade" id="pills-${saha.id}" role="tabpanel"
                                aria-labelledby="pills-${saha.id}-tab">
                                <input type="hidden" value="${data.addweek}" id='getweekval'>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th id="clock-macsuresi">Saat  </th>`;

                                data.filteredDays.forEach(day => {
                                
                                    const bugun = new Date().toISOString().slice(0, 10);
                                    const tarih = day.tarih;
                                    const renk = tarih === bugun ? '#f09f22' : '';

                                    htmlContent += `
                                <th id="dayname" style="color: ${renk} !important">
                                    ${day.tarih} <br> ${day.gun_ismi}
                                </th>`;
                                });

                                data.reservationTimes.forEach(reservation => {

                                    // Reservation saatlerini saat ve dakika olarak ayır
                                    const reservationStartHour = parseInt(reservation.start
                                        .split(':')[0], 10);
                                    const reservationStartMinute = parseInt(reservation.start
                                        .split(':')[1], 10);
                                    const reservationEndHour = parseInt(reservation.end.split(
                                        ':')[0], 10);
                                    const reservationEndMinute = parseInt(reservation.end.split(
                                        ':')[1], 10);

                                    // Reservation zamanlarını düzenle
                                    const startTimeString =
                                        `${reservationStartHour.toString().padStart(2, '0')}:${reservationStartMinute.toString().padStart(2, '0')}`;
                                    const endTimeString =
                                        `${reservationEndHour.toString().padStart(2, '0')}:${reservationEndMinute.toString().padStart(2, '0')}`;
                                    const timeRangeString =
                                        `${startTimeString}-${endTimeString}`;

                                    htmlContent += `<tr id="boxs">
                                                <td id="clock-macsuresi"> ${timeRangeString} </td>`;

                                    data.filteredDays.forEach(day => {
                                        let reserved = false;

                                        data.appointments.forEach(appointment => {
                                     



                                           
                                         
                                             
                                               
                                       if (appointment.date=== day.tarih+" "+startTimeString+":00") {
                                                console.log(appointment.date);
                                                  console.log("*********");
                                                  console.log(day.tarih+" "+startTimeString+":00");
                                                index++;
                                    
                                                htmlContent += `
                                                <td id="${appointment.title === 'DOLU' ? 'dolu' : 'abone'}"
                                                    data-name="${appointment.userName}"
                                                    data-contact="${appointment.userinfo}"
                                                    data-note="${appointment.note}"
                                                    data-eventid="${appointment.id}"
                                                    data-status="${appointment.title}"
                                                    data-id="${saha.id}"
                                                    data-original-content="${appointment.title}"
                                                    data-bs="${reservation.start}"
                                                    data-nowDate="${new Date().toISOString().slice(0, 10)}"
                                                    data-option="${day.tarih}"
                                                    onmouseover="showDataBs(this)"
                                                    onmouseout="hideDataBs(this)"
                                                    onclick="editEvent(this);">
                                                    ${appointment.title}
                                                </td>
                                                `;
                                                reserved = true;
                                            }
                                        });

                                        if (!reserved) {
                                            
                                            index++;
                                             const bugun = new Date().toISOString()
                                                .slice(0, 10);
                                            const tarih = day.tarih;
                                            const renk = tarih === bugun ? '#f0ba65' :
                                                '';
                                            htmlContent += `
                                            <td id="bos" style="background-color:${renk}" data-id="${data.id}"
                                                data-original-content="BOŞ"
                                                data-bs="${reservation.start}"
                                                data-nowDate="${new Date().toISOString().slice(0, 10)}"
                                                data-option="${day.tarih}"
                                                onmouseover="showDataBs(this)"
                                                onmouseout="hideDataBs(this)"
                                                onclick="addEvent(this);">
                                                BOŞ
                                            </td>`;
                                        }

                                    });

                                    htmlContent += `</tr>`;
                                });


                                htmlContent += `</tbody>
                                    </table>
                                </div>
                            </div>`;
                            });



                            dataContainer.innerHTML = htmlContent;
                        })
                        .catch(error => {
                            loader.style.display = 'none'; // Hata durumunda da yükleme göstergesini gizle
                            console.error('Hata:', error);
                        });
                }
                var sahaID = document.getElementById("sahaID").value;
                var addweekElement = document.getElementById("addweek");
                var addweek = addweekElement ? addweekElement.value : 0;

                function handleClick(event) {
                    week = 0;

                    const clickedButton = event.target;
                    const clickedId = clickedButton.id.split("-")[1];
                    fetchData(clickedId, week);
                }



                // İlk taba tıklanınca çağrılsın
                fetchData(sahaID, 0);

                // Tüm tabları dinle
                buttons.forEach(button => {
                    button.addEventListener("click", handleClick);
                });

                var nextbutton = document.getElementById("next");
                if (nextbutton) {
                    nextbutton.addEventListener("click", handleClicknext);
                }


                function handleClicknext(event) {
                    var getgetweekval = document.getElementById("getweekval").value;


                    week = parseInt(getgetweekval) + 1;

                    fetchData(sahaninidsi, week);
                }

                var prewbutton = document.getElementById("prev");
                if (prewbutton) {
                    prewbutton.addEventListener("click", handleClickonceki);
                }


                function handleClickonceki(event) {
                    var getgetweekval = document.getElementById("getweekval").value;


                    week = parseInt(getgetweekval) - 1;

                    fetchData(sahaninidsi, week);
                }
                var prewbutton = document.getElementById("now");
                if (prewbutton) {
                    prewbutton.addEventListener("click", handleClicknow);
                }


                function handleClicknow(event) {
                    var getgetweekval = document.getElementById("getweekval").value;


                    week = 0;

                    fetchData(sahaninidsi, week);
                }




            });

        } catch (error) {
            console.error('Hata:', error);
        }
    </script>

    <input type="hidden" value="{{ $halisaha[0]->id }}" id='sahaID'>



    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-3 text-gray-900 dark:text-gray-100 backgrounds">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-9">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

                            @foreach ($halisaha as $key => $item)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $item->id == $id ? 'active' : '' }}"
                                        id="pills-{{ $item->id }}-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-{{ $item->id }}" type="button" role="tab"
                                        aria-controls="pills-{{ $item->id }}" aria-selected="true">
                                        {{ $item->name }}
                                    </button>
                                </li>
                            @endforeach


                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <div class="float-right">
                            <input type="hidden" value="${data.addweek}" id='addweek'>
                            <button id="prev" class="btn btn-dark"> Önceki </button>
                            <button id="now" class="btn btn-dark" style="background-color: #f09f22;  border:#f09f22">
                                Bugün </button>

                            <button id="next" class="btn btn-dark"> Sonraki </button>


                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">

            </div>
            <div id="loader" class="loader"></div>

        </div>
    </div>





    <script>
        $(document).ready(function() {
            // İçeriği temizle

            $('#pills-tab .nav-link:first').addClass('active');
            // İlk tabın içeriğini göster
            $('#pills-tabContent .tab-pane:first').addClass('show active');


        });
    </script>
    <script>
        function editEvent(identifier) {
            var userName = $(identifier).data('name');
            var contact = $(identifier).data('contact');
            var userinfo = $(identifier).data('note');
            var eventId = $(identifier).data('eventid');

            var sahaID = $(identifier).data('id');
            var status = $(identifier).data('status');


            var selectedDate = $(identifier).data('option');

            selectedHour = $(identifier).data('bs');
            const nowDate = $(identifier).data('nowdate');




            const selectedDateTime = selectedDate + " " + selectedHour;
            const selectedDateTimeString = selectedDateTime;
            const selectedDateTimes = new Date(selectedDateTimeString);
            const formattedDateTime = selectedDateTimes.toLocaleString();





            Swal.fire({
                background: 'white',

                title: "<h5 style='color:black'> Rezervasyon Tarihi: " + formattedDateTime + "</h5>" +
                    "<h5 style='color:black'>İsim: " + userName + "</h5>" +
                    "<h5 style='color:black'> İletişim:" + contact + "</h5>" +
                    "<h5 style='color:black'> Not:" + userinfo + "</h5>",
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
                                    ':id', eventId);
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

                                <label>Ulaşılacak Kişinin İsmi</label>

                               <input type="text" name="userName" class="swal2-input" placeholder="İrtibat İsmi" value=` +
                            userName + `>
                            <label>Telefon Numarasını Başında "0" Olmadan Yazın</label>
                               <input type="tel"  name="userinfo" class="swal2-input"  value= ` + contact + ` placeholder="İrtibat Numarası"></div>
                           <input type="text" name="note" class="swal2-input" placeholder="Not"  value=` + userinfo + `>
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


                            const dateStr = formattedDateTime;


                            $.ajax({
                                type: "POST",
                                url: "{{ route('calender.update') }}",
                                data: {
                                    title: status,

                                    id: eventId,

                                    sahaId: sahaID,
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
        };
    </script>

    <script>
        function addEvent(identifier) {
            const tarih = new Date(); // Şu anki tarih ve saat

            var sahaID = $(identifier).data('id');


            var selectedDate = $(identifier).data('option');

            selectedHour = $(identifier).data('bs');
            const nowDate = $(identifier).data('nowdate');




            const selectedDateTime = selectedDate + " " + selectedHour;
            const selectedDateTimeString = selectedDateTime;
            const selectedDateTimes = new Date(selectedDateTimeString);
            const formattedDateTime = selectedDateTimes.toLocaleString();

            if (selectedDate < nowDate) {
                Swal.fire({
                    background: 'white',
                    title: "<h3 style='color:black'>Geçmiş Zaman'a Ekleme Yapılmaz</h3>",
                });
            } else {
                Swal.fire({
                    background: 'white',
                    title: "<h3 style='color:black'> " +
                        "Bu saat aralığına eklemek istediğinize emin misiniz?" +
                        "<h5 style='color:black'> Seçilen Tarih: " + formattedDateTime + "</h5>"

                        +
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
                                <label>Ulaşılacak Kişinin İsmi</label>
                                <input type="text" name="userName" class="swal2-input" placeholder="İrtibat İsmi">
                                <label>Telefon Numarasını Başında "0" Olmadan Yazın</label>
                                <input type="tel"  name="userinfo" class="swal2-input" placeholder="İrtibat Numarası"  pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                            </div>
                            <input type="text" name="note" class="swal2-input" placeholder="Not">
                        `,
                    showCancelButton: false,
                    confirmButtonText: "Ekle",
                    denyButtonText: `Vazgeç`
                }).then((result) => {
                    if (result.isConfirmed) {
                        const abonelikSuresi = result.value;
                        const userName = $("input[name='userName']").val();
                        const userinfo = $("input[name='userinfo']").val();
                        const note = $("input[name='note']").val();

                        // AJAX isteği
                        $.ajax({
                            type: "POST",
                            url: "{{ route('calender.add') }}",
                            data: {
                                title: abonelikSuresi,
                                sahaId: sahaID,
                                date: formattedDateTime,
                                userName: userName,
                                userinfo: userinfo,
                                note: note,
                                aboneTime: abonelikSuresi,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {

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

                    }
                });
            }


        }
    </script>
@endsection
