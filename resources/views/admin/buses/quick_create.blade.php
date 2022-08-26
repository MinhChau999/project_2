@extends('admin.layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <style>
        .error {
            color: red !important;
        }
        .btn-primary {
            opacity:0.6;
        }
        .btn-primary:hover{
            opacity:1;
        }
    </style>
@endpush
@section('content')

<form action="{{route('admin.buses.quickStore')}}" id="form" method="post">
@csrf
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin thời gian</h4>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="form-group">
                        <label>Năm</label>
                        <select class="select col-md-12" id="year" name="year"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-lg-6">
                        <label>Tuần bắt đầu</label>
                        <select class="select col-md-12" id="week_start" name="week_start"></select>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Tuần kết thúc</label>
                        <select class="select col-md-12" id="week_end" name="week_end"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-lg-6">
                        <label>Ngày bắt đầu</label>
                        <input type="text" placeholder="Ngày bắt đầu" name="date_start" id="date_start"
                            class="form-control" disabled>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Ngày kết thúc</label>
                        <input type="text" placeholder="Ngày kết thúc" name="date_end" id="date_end"
                            class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label>Khoảng thời gian giữa 2 chuyến xe</label>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input class="col-md-12" type="text" id="time_two_buses" name="time_two_buses"
                            style="height: 40px; border: 1px solid #ddd; text-align: center;"> 
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-lg-6">
                        <label>Giờ bắt đầu trong ngày</label>
                        <div class="form-group">
                            <input class="col-md-12" type="text" id="time_start_day" name="time_start_day"
                            style="height: 40px; border: 1px solid #ddd; text-align: center;"> 
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Giờ kết thúc trong ngày</label>
                        <div class="form-group">
                            <input class="col-md-12" type="text" id="time_end_day" name="time_end_day"
                            style="height: 40px; border: 1px solid #ddd; text-align: center;"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Thông tin tuyến đường</h4>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="form-group col-lg-6">
                        <label>Tuyến đi</label>
                        <select class="select col-md-12" id="route_from" name="route_from"></select>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Tuyến về</label>
                        <select class="select col-md-12" id="route_to" name="route_to"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-lg-6">
                        <label>Thời gian di chuyển (giờ)</label>
                        <input type="text" placeholder="Thời gian di chuyển" name="time_move" id="time_move"
                            class="form-control">
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Khoảng cách (km)</label>
                        <input type="text" placeholder="Khoảng cách" name="distance" id="distance"
                            class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-lg-12">
                        <label>Xe không hoạt động 1 ngày trước tuần bắt đầu</label>
                        <div class="col-lg-12">
                            <select class="select col-md-12" id="carriage_free" name="carriage_free[]" multiple style="width:100%;"></select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-group col-lg-6">
                        <label>Danh sách xe tuyến đi</label>
                        <div class="col-lg-12">
                            <select class="select col-md-12" id="carriage_from" name="carriage_from[]" multiple style="width:100%;"></select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Danh sách xe tuyến về</label>
                        <div class="col-lg-12">
                            <select class="select col-md-12" id="carriage_to" name="carriage_to[]" multiple style="width:100%;"></select>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-info col-lg-2" style="color: #ffffff;" onclick="checkCarriages()">Kiểm tra xe</button>
                    <button type="submit" class="btn btn-primary col-lg-2">Tạo nhanh</button>
                </div>
                <div class="text-end row">
                    <div style="color: darkgrey; font-size: 13px">* Lưu ý: chọn tuyến đường và tuần bắt đầu trước khi kiểm tra</div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@push('js')
    {{-- add timepicker --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
    <script>
        function checkCarriages(){
            let route_from = $('#route_from').val();
            let route_to = $('#route_to').val();
            let year = $('#year').val();
            let week = $('#week_start').val();
            $.ajax({
                url: '{{ route('admin.buses.apiCheckCarriage') }}',
                type: 'GET',
                data: {
                    route_from: route_from,
                    route_to: route_to,
                    year: year,
                    week: week,
                },
                success: function(res) { 
                    $("#carriage_from, #carriage_to, #carriage_free").val(null).trigger("change"); 
                    for(let element in res.data){
                        if(res.data[element][0] == 1){
                            $('#carriage_from').select2('trigger', 'select', {
                                data: {
                                    text: res.data[element][1],
                                    id: element
                                }      
                            });   
                        }
                        if(res.data[element][0] == 2){
                            $('#carriage_to').select2('trigger', 'select', {
                                data: {
                                    text: res.data[element][1],
                                    id: element
                                }      
                            });   
                        }
                        if(res.data[element][0] == 0){
                            $('#carriage_free').select2('trigger', 'select', {
                                data: {
                                    text: res.data[element][1],
                                    id: element
                                }      
                            });   
                        }
                    }
                    notify(res);
                },
                error: function(res){
                    notify(res.responseJSON);
                }
            });
        }

        $(document).ready(function() {
             
            // set data for year
            let year = [];
            let startYear = new Date().getFullYear();
            let route_id = $('#route_from').val();
            for (let i = 0; i < 20; i++) {
                year.push({
                    id: i + startYear,
                    text: i + startYear
                });
            }
            $('#year').select2({
                placeholder: 'Chọn năm',
                tags: false,
                data: year
            });

            // set data for week-start and week-end
            let week = [{
                id:'',
                text:'Chọn tuần'
            }];
            for (let i = 1; i <= 53; i++) {
                week.push({
                    id: i,
                    text: "Tuần " + i
                });
            }
            $('#week_start').select2({
                placeholder: 'Chọn tuần bắt đầu',
                tags: true,
                data: week
            });
            $('#week_end').select2({
                placeholder: 'Chọn tuần kết thúc',
                tags: true,
                data: week
            });

            $('#route_from').select2({
                ajax: {
                    url: "{{route('admin.routes.api.name_routes')}}",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: params.term, // search term
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                },
                placeholder: 'Chọn tuyến đường',
            });

            $('#route_to').select2({
                ajax: {
                    url: "{{route('admin.routes.api.name_routes')}}",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: params.term, // search term
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                },
                placeholder: 'Chọn tuyến đường',
            });

            $('#carriage_free, #carriage_from, #carriage_to').select2({
                ajax: {
                    url: "{{route('admin.carriages.api.nameCarriages')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            route_id: route_id == null ? 0 : route_id
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.license_plate,
                                    id: item.id,
                                }
                            })
                        };
                    }
                },
                placeholder: 'Chọn xe',
                allowClear:true,
            });
            
            // set data for time-two-buses 30 minutes
            $('#time_two_buses').timepicker({
                timeFormat: 'H:mm',
                interval: 30,
                minTime: '1',
                maxTime: '23',
                defaultTime: '1',
                startTime: '0:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            // set data for time-start-day and time-end-day
            $('#time_start_day').timepicker({
                timeFormat: 'h:mm p',
                interval: 30,
                minTime: '0',
                maxTime: '23',
                defaultTime: '4',
                startTime: '0:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $('#time_end_day').timepicker({
                timeFormat: 'h:mm p',
                interval: 30,
                minTime: '0',
                maxTime: '23',
                defaultTime: '19',
                startTime: '0:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            // week change
            $('#week_start').on('change', function() {
                let week_start = $('#week_start').val();
                let year = $('#year').val();
                $.ajax({
                    url: '{{ route('admin.buses.apiGetDay') }}',
                    type: 'GET',
                    data: {
                        week_start: week_start,
                        year: year
                    },
                    success: function(res) {
                        $('#date_start').val(res);
                    }
                });
            });

            $('#week_end').on('change', function() {
                let week_start = $('#week_end').val();
                let year = $('#year').val();
                $.ajax({
                    url: '{{ route('admin.buses.apiGetDay') }}',
                    type: 'GET',
                    data: {
                        week_end: week_start,
                        year: year
                    },
                    success: function(res) {
                        $('#date_end').val(res);
                    }
                });
            });

            // route_from change
            $('#route_from').on('change', function() {
                let route_from = $('#route_from').val();
                route_id = $('#route_from').val();
                $.ajax({
                    url: '{{ route('admin.routes.apiGetRouteInverse') }}',
                    type: 'GET',
                    data: {
                        route: route_from
                    },
                    success: function(res) {
                        $('#route_to').empty();
                        $('#route_to').select2({
                            placeholder: 'Chọn tuyến đường',
                            tags: true,
                            data: [{
                                id: res.id,
                                text: res.name
                            }]
                        });
                        $('#time_move').val(res.time);
                        $('#distance').val(res.distance);
                    }
                });
            });

            $('#route_to').on('change', function() {
                let route_to = $('#route_to').val();
                route_id = $('#route_from').val();
                $.ajax({
                    url: '{{ route('admin.routes.apiGetRouteInverse') }}',
                    type: 'GET',
                    data: {
                        route: route_to
                    },
                    success: function(res) {
                        $('#route_from').empty();
                        $('#route_from').select2({
                            placeholder: 'Chọn tuyến đường',
                            tags: true,
                            data: [{
                                id: res.id,
                                text: res.name
                            }]
                        });
                        $('#time_move').val(res.time);
                        $('#distance').val(res.distance);
                    }
                });
            });

            $("#form").validate({
                    rules: {
                        year: {
                            required: true,
                        },
                        week_start: {
                            required: true,
                        },
                        week_end: {
                            required: true,
                        },
                        time_two_buses: {
                            required: true,
                        },
                        time_start_day: {
                            required: true,
                        }, 
                        time_end_day: {
                            required: true,
                        }, 
                        route_from: {
                            required: true,
                        }, 
                        route_to: {
                            required: true,
                        }, 
                        time_move: {
                            required: true,
                        }, 
                        carriage_from: {
                            required: true,
                        },
                        carriage_to: {
                            required: true,
                        },
                    },
                    messages:{
                        year: {
                            required: 'Vui lòng chọn năm',
                        },
                        week_start: {
                            required: 'Vui lòng chọn tuần bắt đầu',
                        },
                        week_end: {
                            required: 'Vui lòng chọn tuần kết thúc',
                        },
                        time_two_buses: {
                            required: 'Vui lòng chọn thời gian giữa 2 chuyến đi',
                        },
                        time_start_day: {
                            required: 'Vui lòng chọn thời gian bắt đầu trong ngày',
                        }, 
                        time_end_day: {
                            required: 'Vui lòng chọn thời gian kết thúc trong ngày',
                        }, 
                        route_from: {
                            required: 'Vui lòng chọn tuyến đi',
                        }, 
                        route_to: {
                            required: 'Vui lòng chọn tuyến về',
                        }, 
                        time_move: {
                            required: 'Vui lòng nhập thời gian di chuyển',
                        }, 
                        carriage_from: {
                            required: 'Vui lòng chọn danh sách xe',
                        },
                        carriage_to: {
                            required: 'Vui lòng chọn danh sách xe',
                        },
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            });
    </script>
@endpush
@endsection
