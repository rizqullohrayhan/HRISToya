@extends('template.main')

@section('css')
<style>
    .fc-day-sun {
        background-color: rgb(179, 39, 39);
        /*your styles goes here*/
    }
    .fc-day-today {
        background: #66fa8b !important;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Cuti Bersama</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    </div>
</div>
@endsection

@section('js')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<script>
    const modal = $('#modal-default');
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'multiMonthYear',
            themeSystem: 'bootstrap5',
            weekends: true,
            events: `{{ route('cutibersama.data') }}`,
            editable: true,
            eventDurationEditable: false,
            dateClick: function (info) {
                $.ajax({
                    url: '{{ route("cutibersama.create") }}',
                    data: {
                        tanggal: info.dateStr,
                    },
                    success: function(res) {
                        modal.html(res).modal('show');

                        $('#form-action').on('submit', function(e){
                            e.preventDefault();
                            const form = this;
                            const formData = new FormData(form);
                            $.ajax({
                                url: form.action,
                                type: form.method,
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (res) {
                                    modal.modal('hide');
                                    $.notify({
                                        message: res.message
                                    },{
                                        type: res.status
                                    });
                                    calendar.refetchEvents();
                                }
                            })
                        })
                    }
                })
            },
            eventClick: function ({event}) {
                let url = '{{ route("cutibersama.edit", "__ID__") }}';
                url = url.replace("__ID__", event.id);
                $.ajax({
                    url: url,
                    success: function(res) {
                        modal.html(res).modal('show');

                        $('.btn-delete-event').on('click', () => deleteEvent(event.id));

                        $('#form-action').on('submit', function(e){
                            e.preventDefault();
                            const form = this;
                            const formData = new FormData(form);
                            $.ajax({
                                url: form.action,
                                type: form.method,
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (res) {
                                    modal.modal('hide');
                                    $.notify({
                                        message: res.message
                                    },{
                                        type: res.status
                                    });
                                    calendar.refetchEvents();
                                }
                            })
                        })
                    }
                })
            },
            eventDrop: function ({event}){
                let url = '{{ route("cutibersama.update", "__ID__") }}';
                url = url.replace("__ID__", event.id);
                $.ajax({
                    url: url,
                    method: 'put',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json',
                    },
                    data: {
                        id: event.id,
                        tanggal: event.startStr,
                        keterangan: event.title,
                    },
                    success: function(res) {
                        $.notify({
                            message: res.message
                        },{
                            type: res.status
                        });
                        calendar.refetchEvents();
                    }
                })
            },
        });
        calendar.render();

        function deleteEvent(id) {
            $('button').prop('disabled', true);
            let url = '{{ route("cutibersama.destroy", "__ID__") }}';
            url = url.replace("__ID__", id);
            $.ajax({
                url: url,
                method: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    // 'accept': 'application/json',
                },
                success: function(res) {
                    modal.modal('hide');
                    $.notify({
                        message: res.message
                    },{
                        type: res.status
                    });
                    calendar.refetchEvents();
                }
            })
        }
    });
</script>

@endsection
