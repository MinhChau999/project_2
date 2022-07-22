@extends('admin.layout.master')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Sửa Chuyến Xe</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.buses.update', $buses)}}" id="form-create-post" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label">Tuyến đường</label>
                                <select class="form-control" name="route" id="route">
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label">Điểm đi</label>
                                <select class="form-control" name="from" id="from">
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="col-form-label">Điểm đến</label>
                                <select class="form-control" name="to" id="to">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Xe</label>
                            <div class="col-md-10">
                                <select class="form-control" name="car" id="license-plate">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Tài xế</label>
                            <div class="col-md-10">
                                <select class="form-control" name="driver" id="driver">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Ngày khởi hành</label>
                            <div class="col-md-10">
                                <input class="form-control" name="date" id="date" type="date" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Giờ đi</label>
                            <div class="col-md-10">
                                <input class="form-control" name="time" id="time" type="time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Giá chuyến đi</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" name="price" id="price" placeholder="Giá">
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button class="btn btn-primary" type="submit" onclick="alert('Vui lòng chờ 5s !!! Cảm ơn')">Sửa Chuyến Xe</button>
                            <a href="{{route('admin.buses.index')}}" class="btn btn-link">Quay Lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                // set value for '#date'
                let date = '{{$buses->departure_time}}'.split(' ')[0];
                let time = '{{$buses->departure_time}}'.split(' ')[1].split(':')[0] + ':' + '{{$buses->departure_time}}'.split(' ')[1].split(':')[1];
                $('#date').val(date);
                $('#time').val(time);
                $('#price').val('{{$buses->price}}');

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
                                        id: item.id,
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Chọn tuyến đường',
                    allowClear:true
                });
                $('#route').select2('trigger', 'select', {
                    data: {
                        text: '{{$route->name}}',
                        id: {{$route->id}}
                    }
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
                                        id: item.id,
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Nhập tên tài xế',
                    allowClear:true
                });
                $('#driver').select2('trigger', 'select', {
                    data: {
                        text: '{{$driver->name}}',
                        id: {{$driver->id}}
                    }
                });
                
                $('#license-plate').select2({
                    ajax: {
                        url: "{{route('admin.carriages.api.nameCarriages')}}",
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
                                        id: item.id,
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Chọn bảng số xe',
                    allowClear:true,
                });
                $('#license-plate').select2('trigger', 'select', {
                    data: {
                        text: '{{$car->license_plate}}',
                        id: {{$car->id}}
                    }
                });

                $('#from').select2({
                    ajax: {
                        url: "{{route('admin.cities.api.city')}}",
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
                                        text: item.name,
                                        id: item.id,
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Điểm đi',
                    allowClear:true,
                });
                $('#from').select2('trigger', 'select', {
                    data: {
                        text: '{{$from->name}}',
                        id: {{$from->id}}
                    }
                });

                $('#to').select2({
                    ajax: {
                        url: "{{route('admin.cities.api.city')}}",
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
                                        text: item.name,
                                        id: item.id,
                                    }
                                })
                            };
                        }
                    },
                    placeholder: 'Điểm đến',
                    allowClear:true,
                });
                $('#to').select2('trigger', 'select', {
                    data: {
                        text: '{{$to->name}}',
                        id: {{$to->id}}
                    }
                });

                // parent change and child change
                $('#route').change(function() {
                    let route_id = $(this).val();
                    $.ajax({
                        url: "{{route('admin.routes.api.apiGetCityByRoute')}}",
                        type: 'GET',
                        data: {
                            route_id: route_id,
                        },
                        success: function(data) {
                            // 'from' select2 change
                            $('#from').select2({
                                ajax: {
                                    url: "{{route('admin.cities.api.city')}}",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term, // search term
                                            city_id: (data[0] != null) ? data[0].city_start_id : null,
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
                                    }
                                },
                                placeholder: 'Điểm đi',
                                allowClear:true,
                            });

                            // 'to' select2 change data
                            $('#to').select2({
                                ajax: {
                                    url: "{{route('admin.cities.api.city')}}",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term, // search term
                                            city_id: (data[0] != null) ? data[0].city_end_id : null,
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
                                    }
                                },
                                placeholder: 'Điểm đến',
                                allowClear:true,
                            });

                            // 'carriage' select2 change url data
                            $('#license-plate').select2({
                                ajax: {
                                    url: "{{route('admin.carriages.api.nameCarriages')}}",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term, // search term
                                            route_id: route_id,
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
                                placeholder: 'Chọn bảng số xe',
                                allowClear:true,
                            });

                            // 'driver' select2 change url data
                            $('#driver').select2({
                                ajax: {
                                    url: "{{route('admin.users.api.name_drivers')}}",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term, // search term
                                            route_id: route_id,
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
                                    }
                                },
                                placeholder: 'Nhập tên tài xế',
                                allowClear:true,
                            });

                        }
                    });
                });

                $('#from, #to').change(function(){
                    let from = $('#from').val();
                    let to = $('#to').val();
                    $.ajax({
                        url: "{{route('admin.routes.api.apiGetRouteByCity')}}",
                        type: 'GET',
                        data: {
                            city_start_id: from,
                            city_end_id: to,
                        },
                        success: function(data) {
                            // 'route' select2 change url data
                            $('#route').select2({
                                ajax: {
                                    url: "{{route('admin.routes.api.name_routes')}}",
                                    dataType: 'json',
                                    delay: 250,
                                    data: function (params) {
                                        return {
                                            q: params.term, // search term
                                            id: (data[0] != null) ? data[0].id : null,
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
                                    }
                                },
                                placeholder: 'Chọn tuyến đường',
                                allowClear:true,
                            });
                        }
                    });
                });

                $('#license-plate').change(function() {
                    let car_id = $(this).val();
                    let route_id = $('#route').val();

                    $('#driver').select2({
                        ajax: {
                            url: "{{route('admin.users.api.name_drivers')}}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    route_id: route_id,
                                    car_id: car_id,
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
                            }
                        },
                        placeholder: 'Nhập tên tài xế',
                        allowClear:true,
                    });
                });

                $('#driver').change(function() {
                    let driver_id = $(this).val();
                    let route_id = $('#route').val();
                    $('#license-plate').select2({
                        ajax: {
                            url: "{{route('admin.carriages.api.nameCarriages')}}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    route_id: route_id,
                                    driver_id: driver_id,
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
                        placeholder: 'Chọn bảng số xe',
                        allowClear:true,
                    });
                });

                $('#time').change(function(){
                    $.ajax({
                        url: "{{route('admin.buses.api.apiGetPrice')}}",
                        type: 'GET',
                        data: {
                            route_id: $('#route').val(),
                            driver_id: $('#driver').val(),
                            car_id: $('#license-plate').val(),
                        },
                        success: function(data) {
                            $('#price').val(data.price);
                        }
                    })
                });
            });
        </script>
    @endpush
@endsection
