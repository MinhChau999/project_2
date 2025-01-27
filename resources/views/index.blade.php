@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="{{asset('css/jquery.toast.min.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://resources/demos/style.css">
{{--    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>--}}
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{asset('js/datepicker-vi.js')}}" type="text/javascript"></script>
    <style>
        .f-w-700{
            font-weight:700 !important;
        }
    </style>
    <script>
        var dateToday = new Date();
        $(function() {
            $( "#departure-day" ).datepicker({
                showButtonPanel: true,
                minDate: dateToday
            });
        });
        $('#departure-day').datepicker($.datepicker.regional["vi"]);
    </script>
@endpush
@section('content')
<div class="hero-wrap js-fullheight" style="background-image:url({{asset('images/background_thu_duc.jpg')}})"
     data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container" id="book_ticket">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center"
             data-scrollax-parent="true">
            <div class="col-md-9 text text-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                <a href="https://vimeo.com/709802477"
                   class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
                    <span class="ion-ios-play"></span>
                </a>
                <h1 data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" class="f-w-700">Tôi sẽ cho bạn một chuyến đi thoải mái và an toàn</h1>
            </div>
        </div>
    </div>
</div>
<!-- Ảnh bìa -->
<section class="ftco-section ftco-no-pb ftco-no-pt">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-wrap-1 ftco-animate p-4" style="border-radius:15px;box-shadow: 5px 5px #847979c4;">
                    <form action="{{route('applicant.book_ticket_2')}}" method="get" class="search-property-1">
                        <input type="hidden" name="step" value="2">
                        <div class="row">
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Nơi đi</label>
                                    <div class="form-field">
                                        <div class="select-wrap">
                                            <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                            <select name="city_start" id="" class="form-control">
                                                <option value="-1" selected>Chọn nơi đi</option>
                                                @foreach($city_start as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Nơi đến</label>
                                    <div class="form-field">
                                        <div class="select-wrap">
                                            <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                            <select name="city_end" id="" class="form-control">
                                                <option value="-1">Chọn nơi đến</option>
                                                @foreach($city_end as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-items-end">
                                <div class="form-group">
                                    <label for="#">Ngày khởi hành</label>
                                    <div class="form-field">
                                        <div class="icon"><span class="ion-ios-calendar"></span></div>
                                        <input type="text" id="departure-day" name="departure_time" class="form-control" placeholder="Chọn ngày khởi hành">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg align-self-end">
                                <div class="form-group">
                                    <div class="form-field">
                                        <input type="submit" value="Tìm vé xe" class="form-control btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end Ảnh bìa -->
<!-- Tiện tích -->
<section class="ftco-section services-section bg-light">
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-6 order-md-last heading-section pl-md-5 ftco-animate">
                <h2 class="mb-4">Tiện Ích Nhà Xe</h2>
                <p>Chúng Tôi luôn muốn cho quý khách những cảm giác thoải mái nhất khi đi xe !!!</p>
                <p>Vì vậy chúng tôi luôn mang cho quý khách những tiện ích tốt nhất ở trên xe !!!</p>
{{--                <p><a href="#" class="btn btn-primary py-3 px-4">Search Destination</a></p>--}}
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
{{--                            <div class="icon"><span class="flaticon-paragliding"></span></div>--}}
                            <div class="icon"><span class="fas fa-wifi" ></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Free Wifi</h3>
                                <p>Tất cả các xe của Thu Đức đều được trang bị wifi miễn phí tốc độ cao. Bạn có thể thư giãn bằng cách lướt web, mạng xã hội, kiểm tra email hoặc chat chit với bạn bè.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="fas fa-battery-empty"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Cổng Sạc Điện Thoại</h3>
                                <p>Chúng tôi hiểu rằng hành trình của những hành khách hiện đại ngày nay không thể thiếu điện thoại thông minh. Hãy an tâm vì trên xe luôn có cổng sạc điện thoại ở vị trí thuận tiện nhất cho bạn.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="fas fa-toilet"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Toilet Trên Xe</h3>
                                <p>Thấu hiểu được những “nỗi niềm” của hành khách khi trải nghiệm trên những chuyến xe đường dài, hiện các dòng xe Limousine của Tiến Oanh đều được trang bị WC trên xe giúp hành khách yên tâm ” Giải quyết nỗi buồn ” cho các chuyến đi xa.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                        <div class="media block-6 services d-block">
                            <div class="icon"><span class="fas fa-chair"></span></div>
                            <div class="media-body">
                                <h3 class="heading mb-3">Ghế massage</h3>
                                <p>Tương tự với những “nỗi niềm” trên xe là sự mệt mỏi khi đi trên những cung đường dài, giờ đây các dòng xe VIP của Thu Đức còn trang bị hệ thống massage với đa chế độ , giúp cho hành trình của bạn thêm trọn vẹn. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end tiện ích -->
<section class="ftco-section ftco-no-pt">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4">Lộ Trình Phổ Biến</h2>
            </div>
        </div>
        <div class="row">
            @foreach($routes as $route)
            <div class="col-md-4 ftco-animate">
                <div class="project-wrap">
                    <a href="{{route('applicant.book_ticket_1')}}?step=1&city_start={{$route->city_start_id}}&city_end={{$route->city_end_id}}#id_book_ticket" class="img"
                       style="background-image:url({{asset($route->img)}})"></a>
                    <div class="text p-4">
                        <a href="{{route('applicant.book_ticket_1')}}?step=1&city_start={{$route->city_start_id}}&city_end={{$route->city_end_id}}#id_book_ticket"><span class="price" style="text-align:center">Từ {{$route->price}} Đ/Vé</span></a>
{{--                        <span class="days">Ngày Thường</span>--}}
                        <h3><a href="{{route('applicant.book_ticket_1')}}?step=1&city_start={{$route->city_start_id}}&city_end={{$route->city_end_id}}#id_book_ticket">{{$route->name}}</a></h3>
                        <p class="location" style="display:inline-block"><span class="fas fa-location-arrow"></span> {{$route->distance}}km</p>
                        <p class="location" style="display:inline-block;margin-left: 10px;"><span class="fas fa-stopwatch"></span> {{$route->distance}}h</p>
                        <ul>
                            <li><span class="fas fa-wifi"></span></li>
                            <li><span class="fas fa-fan"></span></li>
                            <li><span class="fas fa-prescription-bottle"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<section class="ftco-section testimony-section bg-bottom"
         style="background-image:url(images/xbg_3.jpg.pagespeed.ic.G_E5bTFaP7.jpg)">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-7 text-center heading-section ftco-animate">
                <h2 class="mb-4">Nhận Xét Khách Hàng</h2>
            </div>
        </div>
        <div class="row ftco-animate">
            <div class="col-md-12">
                <div class="carousel-testimony owl-carousel ftco-owl">
                    <div class="item">
                        <div class="testimony-wrap py-4">
                            <div class="text">
                                <p class="mb-4">Chuyến xe đi an toàn , tài xế nhiệt tình , 10 điểm nhà xe.</p>
                                <div class="d-flex align-items-center">
                                    <div class="user-img"
                                         style="background-image:url(https://upload.wikimedia.org/wikipedia/commons/8/8c/Cristiano_Ronaldo_2018.jpg)"></div>
                                    <div class="pl-3">
                                        <p class="name">Cristiano Ronaldo</p>
                                        <span class="position">Cầu thủ bóng đá</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="testimony-wrap py-4">
                            <div class="text">
                                <p class="mb-4">Nhà xe nhiệt tình , lái xe an toàn, phù hợp với tôi.</p>
                                <div class="d-flex align-items-center">
                                    <div class="user-img"
                                         style="background-image:url(https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcR_BSXPlBjoBeJruSaCamv7kQuMNjoIIWX0CITXUVoapFCbRM9g)"></div>
                                    <div class="pl-3">
                                        <p class="name">Lionel Messi</p>
                                        <span class="position">Cầu thủ bóng đá</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="testimony-wrap py-4">
                            <div class="text">
                                <p class="mb-4">Nhà xe nhiệt tình, xe đi an toàn, sạch sẽ.</p>
                                <div class="d-flex align-items-center">
                                    <div class="user-img" style="background-image:url(https://upload.wikimedia.org/wikipedia/commons/b/bc/Bra-Cos_%281%29.jpg)"></div>
                                    <div class="pl-3">
                                        <p class="name">Neymar da Silva Santos Júnior</p>
                                        <span class="position">Cầu thủ bóng đá</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="testimony-wrap py-4">
                            <div class="text">
                                <p class="mb-4">Nhà Xe an toàn sạch sẽ</p>
                                <div class="d-flex align-items-center">
                                    <div class="user-img"
                                         style="background-image:url(http://t0.gstatic.com/licensed-image?q=tbn:ANd9GcQIjnNG168e1GZWlzuE4SmKCkiOdRrxBtGQecwI3irgVn8jbgiCxp_Glxp83Mjj3sJ6)"></div>
                                    <div class="pl-3">
                                        <p class="name">Erling Haaland</p>
                                        <span class="position">Cầu thủ bóng đá</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="testimony-wrap py-4">
                            <div class="text">
                                <p class="mb-4">Nhà xe đi an toàn , nhiệt tình , sạch sẽ phù hợp với tôi , tôi cho 10 điểm.</p>
                                <div class="d-flex align-items-center">
                                    <div class="user-img"
                                         style="background-image:url(https://ss-images.saostar.vn/pc/1608962665828/do-mixi-1.png)"></div>
                                    <div class="pl-3">
                                        <p class="name">Phùng Thanh Độ</p>
                                        <span class="position">Streamer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h2 class="mb-4">Tin Tức</h2>
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-md-4 d-flex ftco-animate">
                <div class="blog-entry justify-content-end">
                    <a href="blog-single.html" class="block-20"
                       style="background-image:url(images/ximage_1.jpg.pagespeed.ic.kmZrkQhS0S.jpg)">
                    </a>
                    <div class="text mt-3 float-right d-block">
                        <div class="d-flex align-items-center mb-4 topp">
                            <div class="one">
                                <span class="day">21</span>
                            </div>
                            <div class="two">
                                <span class="yr">2019</span>
                                <span class="mos">August</span>
                            </div>
                        </div>
                        <h3 class="heading"><a href="#">Why Lead Generation is Key for Business Growth</a></h3>
                        <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex ftco-animate">
                <div class="blog-entry justify-content-end">
                    <a href="blog-single.html" class="block-20"
                       style="background-image:url(images/ximage_2.jpg.pagespeed.ic.JE_SLGedc0.jpg)">
                    </a>
                    <div class="text mt-3 float-right d-block">
                        <div class="d-flex align-items-center mb-4 topp">
                            <div class="one">
                                <span class="day">21</span>
                            </div>
                            <div class="two">
                                <span class="yr">2019</span>
                                <span class="mos">August</span>
                            </div>
                        </div>
                        <h3 class="heading"><a href="#">Why Lead Generation is Key for Business Growth</a></h3>
                        <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex ftco-animate">
                <div class="blog-entry">
                    <a href="blog-single.html" class="block-20"
                       style="background-image:url(images/ximage_3.jpg.pagespeed.ic.JFKhYEne4E.jpg)">
                    </a>
                    <div class="text mt-3 float-right d-block">
                        <div class="d-flex align-items-center mb-4 topp">
                            <div class="one">
                                <span class="day">21</span>
                            </div>
                            <div class="two">
                                <span class="yr">2019</span>
                                <span class="mos">August</span>
                            </div>
                        </div>
                        <h3 class="heading"><a href="#">Why Lead Generation is Key for Business Growth</a></h3>
                        <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    @push('js')
        <script src="{{asset('js/jquery.toast.min.js')}}"></script>
        <script>
            $(function() {
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                $.toast({
                    heading: 'Error',
                    text: '{{ $error }}',
                    icon: 'error',
                    position: 'top-right',
                    showHideTransition: 'slide',
                });
                @endforeach
                @endif
                @if (session()->has('success'))
                $.toast({
                    heading: 'Import Success',
                    text: '{{session()->get('success')}}',
                    icon: 'success',
                    position: 'top-right',
                    showHideTransition: 'slide',
                });
                @endif
                @if (session()->has('error'))
                $.toast({
                    heading: 'Error',
                    text: '{{session()->get('error')}}',
                    icon: 'error',
                    position: 'top-right',
                    showHideTransition: 'slide',
                });
                @endif
            });
        </script>
        <script>
            $(document).ready( function() {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today = dd + '/' + mm + '/' + yyyy;
                $('#departure-day').val(today);
            });
        </script>
    @endpush
@endsection
