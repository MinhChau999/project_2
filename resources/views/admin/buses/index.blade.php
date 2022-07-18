@extends('admin.layout.master')
@push('css')
    <link rel="stylesheet" href="{{asset('plugins/datatables/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        ul.pagination {
            margin-bottom: 1rem!important;
            justify-content: center!important;
        }
        .select2-container{
            display:block!important;
        }
        table.table td a:hover {
            color: #ff0080;
        }
    </style>
@endpush
@section('content')
    <div class="page-header">
        <div class="row" style="position:relative">
            <div class="col-auto text-right" style="
    position: absolute;
    right: 0;
    top: -61px;
">
                <a class="btn btn-white filter-btn" id="filter_search">
                    <i class="fas fa-filter"></i>
                </a>
                <a class="btn btn-white filter-btn add-button ml-3" id="add_btn">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    {{--  Add  --}}
    <div class="card filter-card" id="add_show" style="display: none;" >
        <div class="card-body pb-0">
                <div class="row filter-row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <a href="{{route('admin.buses.create')}}"><button type="button" class="btn btn-info btn-lg" style="font-size: 15px;">Add Bus</button></a>
                            </div>
                        </div>
                </div>
        </div>
    </div>
    {{-- End Add --}}
    {{-- Filter --}}
    <div class="card filter-card" id="filter_inputs">
        <div class="card-body pb-0">
                <div class="row filter-row">                        
                        {{-- Filter time --}}
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>From Date</label>
                                {{-- <div class="cal-icon"> --}}
                                    <input class="form-control" type="date" name="min" id="min">
                                {{-- </div> --}}
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>To Date</label>
                                {{-- <div class="cal-icon"> --}}
                                    <input class="form-control" type="date" name="max" id="max">
                                {{-- </div> --}}
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>Time</label>
                                <input type="time" class="form-control" name="time" id="time">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                        </div>
                        {{-- End Filter time --}}
                        <div class="col-sm-6 col-md-3">
                            <form>
                                <div class="form-group">
                                    <label for="level">Route</label>
                                    <select class="form-control" name="route" id="route" style="text-align: center">
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <form>
                                <div class="form-group">
                                    <label for="level">Carriage</label>
                                    <select class="form-control" name="license-plate" id="license-plate" style="text-align: center">
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <form>
                                <div class="form-group">
                                    <label for="level">Driver</label>
                                    <select class="form-control" name="driver" id="driver" style="text-align: center">
                                    </select>
                                </div>
                            </form>
                        </div>
                </div>
        </div>
    </div>
    {{-- End Filter --}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- thiếu class datatable-->
                        <table class="table table-hover table-center mb-0" id="table-index" style="text-align: center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tuyến đường</th>
                                <th>Ngày khởi hành</th>
                                <th>Giờ đi</th>
                                <th>Thời gian</th>
                                <th>Khoảng cách</th>
                                <th>Giá vé</th>
                                <th>Xe</th>
                                <th>Tài xế</th>
                                <th>Sửa</th>
                                <th>Xoá</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="{{asset('plugins/datatables/datatables.min.js')}}"></script>
        <script>
            // Extend dataTables search
            $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var min = new Date($('#min').val());
                    var max = new Date($('#max').val());
                    var date = new Date( data[2] );
            
                    if (
                        ( min === null && max === null ) ||
                        ( min === null && date <= max ) ||
                        ( min <= date   && max === null ) ||
                        ( min <= date   && date <= max )
                    ) {
                        return true;
                    }
                    return false;
                    }
                );
                
            $(document).on('click', '#add_btn', function() {
                $('#add_show').slideToggle("slow");
            });

            $(document).ready(function() {
                $('#route').select2({
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
                                        id: item.name
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Nhập tên chuyến đi',
                    allowClear:true
                });

                $('#driver').select2({
                    ajax: {
                        url: "{{route('admin.users.api.name_drivers')}}",
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
                                        id: item.name
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Nhập tên tài xế',
                    allowClear:true
                });
                
                $('#license-plate').select2({
                    ajax: {
                        url: "{{route('admin.carriages.api.name_carriages')}}",
                        dataType: 'json',
                        delay: 250,
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
                                        text: item.license_plate,
                                        id: item.license_plate,
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Chọn bảng số xe',
                    allowClear:true,
                });
                
                let table = $('#table-index').DataTable({
                        destroy: true,
                        dom: 'ltrp',
                        select: true,
                        lengthMenu:[5,10,20,25,50,100],
                        processing: true,
                        serverSide: true,
                        ajax: '{!! route('admin.buses.api') !!}',
                        columns: [
                            { data: 'id', name: 'id' },
                            { 
                                data: 'route_name',
                                render: function (data, type, row) {
                                    return `<a href="#">${data}</a>`
                                }
                            },
                            { 
                                data: 'date',
                                name:'date',
                                // convert date to string
                                // render: function(data, type, row) {
                                //     return moment(data).format('DD/MM/YYYY');
                                // }
                            },{ 
                                data: 'departure_time', 
                                // convert time to string
                                render: function(data, type, row) {
                                    return moment(data).format('hh:mm A');
                                }
                            },
                            { 
                                data: 'time', 
                                render: function(data,type,row){
                                    return data + 'h';
                                }
                            },
                            { 
                                data: 'distance',
                                render: function(data,type,row){
                                    return data + 'km';
                                }
                            },
                            { 
                                data: 'price',
                                render: function(data,type,row){
                                    return `${data} <sup>đ</sup>`;
                                }
                            },
                            { data: 'license_plate', name: 'license_plate' },
                            { data: 'driver_name', name: 'driver_name' },
                            {   
                                data: 'edit',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `<a class="btn btn-success" href="${data}" style="color:white!important;">Edit</a>`;
                                }
                            },
                            {                             
                                data: 'delete',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, row, meta) {
                                    return `<form action="${data}" method="post">
                                        @csrf
                                        @method('DELETE')
                                    <button type='button' class="btn btn-danger" id="btn-delete" >Delete</button>
                                    </form>`;
                                }
                            },
                        ],
                });
                // filter
                $('#route').change(function() {
                    table.column(1).search(this.value).draw();
                });
                $('#license-plate').change(function() {
                    table.column(7).search(this.value).draw();
                });
                $('#driver').change(function() {
                    table.column(8).search(this.value).draw();
                });
                $('#min, #max').change(function() {
                    console.log(new Date($('#min').val()));
                    table.draw();
                });
                $('#time').change(function() {
                    table.column(3).search(this.value).draw();
                });
            });

        </script>
    @endpush
@endsection
