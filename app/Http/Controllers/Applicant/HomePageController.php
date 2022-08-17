<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\CarriageCategoryEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\SeatTypeEnum;
use App\Events\ApplicantOrderEvent;
use App\Events\UserCreateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\StoreInfoCustomerRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Bill;
use App\Models\Bill_detail;
use App\Models\Buses;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Route;
use App\Models\Route_driver_car;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class HomePageController extends Controller
{
    public function index()
    {
        $routes = Route::query()->with('city_start')->with('city_end')
            ->selectRaw("routes.*,sum(route_driver_cars.pin) as pin")
            ->leftJoin('route_driver_cars','routes.id','=','route_driver_cars.route_id')
            ->groupBy("id")
            ->orderBy("pin","desc")
        ->get()
        ->map(function ($each) {
            $each->city_start_name = $each->city_start->pluck('name')[0];
            $each->city_end_name = $each->city_end->pluck('name')[0];
            $each->img = "upload/" . $each->images;
            if(isset($each->pin)){
                $price = Route_driver_car::query()
                    ->selectRaw("route_id,MIN(price) as price")
                    ->where('route_id','=',$each->id)
                    ->groupBy('route_id')
                    ->pluck('price')
                    ->toArray();
                $each->price = number_shorten($price[0]);
            }else{
                $each->price = null;
            }
            unset($each->city_start);
            unset($each->city_end);
            unset($each->images);
            return $each;
        });
        $i = 0;
        foreach ($routes as $each){
            $arr['city_start'][$each->city_start_id] = $each->city_start_name;
            $arr['city_end'][$each->city_end_id] = $each->city_end_name;
            if($each->pin == 0||$each->pin===null){
                unset($routes[$i]);
            }
            $i ++;
        }

        $array['city_start'] = array_unique($arr['city_start']);
        $array['city_end'] = array_unique($arr['city_end']);
        return view('index',[
            'city_start' => $array['city_start'],
            'city_end' => $array['city_end'],
            'routes' => $routes,
        ]);
    }

    public function book_ticket(Request $request)
    {
//        dd($request);
        $arr_bus =[];
        $arr_route =[];
        $array =[];
        $arr_routes = [];
        $arr_location = [];
        $bus =[];
        $seatTypes =[];
        if(!isset($request->city_start)||!isset($request->city_end)||!isset($request->departure_time)||$request->city_start==-1||$request->city_end ==-1){
            $request->step = 1;
            //        step 1
            $routes = Route::query()->with('city_start')->with('city_end')
                ->selectRaw("routes.*,sum(route_driver_cars.pin) as pin")
                ->leftJoin('route_driver_cars','routes.id','=','route_driver_cars.route_id')
                ->groupBy("id")
                ->orderBy("pin","desc")
                ->get()
                ->map(function ($each) {
                    $each->city_start_name = $each->city_start->pluck('name')[0];
                    $each->city_end_name = $each->city_end->pluck('name')[0];
                    $each->img = "upload/" . $each->images;
                    if(isset($each->pin)){
                        $price = Route_driver_car::query()
                            ->selectRaw("route_id,MIN(price) as price")
                            ->where('route_id','=',$each->id)
                            ->groupBy('route_id')
                            ->pluck('price')
                            ->toArray();
                        $each->price = number_shorten($price[0]);
                    }else{
                        $each->price = null;
                    }
                    unset($each->city_start);
                    unset($each->city_end);
                    unset($each->images);
                    return $each;
                });
            $i = 0;
            foreach ($routes as $each){
                $arr['city_start'][$each->city_start_id] = $each->city_start_name;
                $arr['city_end'][$each->city_end_id] = $each->city_end_name;
                if($each->pin == 0||$each->pin===null){
                    unset($routes[$i]);
                }
                $i ++;
            }

            for($i =0 ; $i<=2;$i++){
                $arr_routes[$i] = $routes[$i];
            }

            $array['city_start'] = array_unique($arr['city_start']);
            $array['city_end'] = array_unique($arr['city_end']);
        }else if($request->step == 2){
//        step2
            // Load seat type enum
//            dd($request);
            $filter_price = ($request->filter_price!='') ? $request->filter_price : '';
            $filter_seat_type = ($request->filter_seat_type!='') ? $request->filter_seat_type : '';
            $filter_hour = ($request->filter_hour!='') ? $request->filter_hour : '';
            switch($filter_hour){
                case 1:
                    $arr_filter_hour['start'] = '00:00:00';
                    $arr_filter_hour['end'] = '06:00:00';
                    break;
                case 2:
                    $arr_filter_hour['start'] = '06:00:00';
                    $arr_filter_hour['end'] = '12:00:00';
                    break;
                case 3:
                    $arr_filter_hour['start'] = '12:00:00';
                    $arr_filter_hour['end'] = '18:00:00';
                    break;
                case 4:
                    $arr_filter_hour['start'] = '18:00:00';
                    $arr_filter_hour['end'] = '23:59:59';
                    break;
                default:
                    $arr_filter_hour['start'] = '00:00:00';
                    $arr_filter_hour['end'] = '23:59:59';
                    break;
            }
            $seatTypes = SeatTypeEnum::getArrayView();
            $request->step = 2;
            $city_start = (int)$request->city_start;
            $city_end = (int)$request->city_end;
            $departure_time = str_replace('/', '-',$request->departure_time);
            $departure_time = date("Y-m-d", strtotime($departure_time));
//            dd($departure_time);
//            dd($request);
            try{
                $route_id = Route::query()->where('city_start_id',$city_start)->where('city_end_id',$city_end)->pluck('id')[0];
//            dd($route_id);
                $arr_route = Route::query()->where('id',$route_id)->get()->toArray()[0];
//            dd($arr_route['name']);
                $arr_bus = Buses::query()->join('route_driver_cars', 'route_driver_cars.id', '=', 'buses.route_driver_car_id')
                    ->join('routes', 'routes.id', '=', 'route_driver_cars.route_id')
                    ->join('carriages', 'carriages.id', '=', 'route_driver_cars.car_id')
                    ->whereDate('departure_time','=',$departure_time)
                    ->whereTime('departure_time', '>=', $arr_filter_hour['start'])
                    ->whereTime('departure_time', '<', $arr_filter_hour['end'])
                    ->where('routes.city_start_id','=',$city_start)
                    ->where('routes.city_end_id','=',$city_end)
                    ->select('buses.*', 'routes.name', 'routes.id as route_id', 'routes.time',
                        'routes.distance', 'carriages.id as car_id','carriages.category','carriages.seat_type','carriages.default_number_seat','carriages.license_plate',
                        'route_driver_cars.price as route_price')
                    ->get()->map(function($each){
                        $each->category_car = CarriageCategoryEnum::getKeyByValue(($each->category));
                        $each->seat_type_car = SeatTypeEnum::getKeyByValue(($each->seat_type));
                        $each->license_plate_car = $each ->license_plate;
                        $each->route_name = $each ->name;
                        $each->remaining_seats = $each ->default_number_seat - $each->slot;
                        return $each;
                    })->where('remaining_seats','!=','0');
                if($filter_price != ''){
                    $arr_bus=$arr_bus->orderBy('route_price', $filter_price);
                }
                if($filter_seat_type != ''){
                    $arr_bus=$arr_bus->where('seat_type','=',$filter_seat_type);
//                    dd();
                }
                if($arr_bus->isEmpty()){
                    $request->session()->flash('error', 'Không tim thấy tuyến xe hoặc nhà xe không có chuyến');
                    $request->step = 1;
                    return redirect(Request::url());
                }
//                dd($arr_bus);
                $select_locations = Location::query()->where('city_id',$city_start)->get()->toArray();
//            dd($select_locations);
                foreach($select_locations as $each){
                    if($each['name'] == null){
                        $arr_location[$each['id']] ='';
                    }else{
                        $arr_location[$each['id']] = $each['name'] . ' - ';
                    }
                    $arr_location[$each['id']] .= $each['address'].' - '.$each['district'];
                }
            }
            catch(\Throwable $e){
                $request->step = 1;
                $routes = Route::query()->with('city_start')->with('city_end')
                    ->selectRaw("routes.*,sum(route_driver_cars.pin) as pin")
                    ->leftJoin('route_driver_cars','routes.id','=','route_driver_cars.route_id')
                    ->groupBy("id")
                    ->orderBy("pin","desc")
                    ->get()
                    ->map(function ($each) {
                        $each->city_start_name = $each->city_start->pluck('name')[0];
                        $each->city_end_name = $each->city_end->pluck('name')[0];
                        $each->img = "upload/" . $each->images;
                        if(isset($each->pin)){
                            $price = Route_driver_car::query()
                                ->selectRaw("route_id,MIN(price) as price")
                                ->where('route_id','=',$each->id)
                                ->groupBy('route_id')
                                ->pluck('price')
                                ->toArray();
                            $each->price = number_shorten($price[0]);
                        }else{
                            $each->price = null;
                        }
                        unset($each->city_start);
                        unset($each->city_end);
                        unset($each->images);
                        return $each;
                    });
                $i = 0;
                foreach ($routes as $each){
                    $arr['city_start'][$each->city_start_id] = $each->city_start_name;
                    $arr['city_end'][$each->city_end_id] = $each->city_end_name;
                    if($each->pin == 0||$each->pin===null){
                        unset($routes[$i]);
                    }
                    $i ++;
                }

                for($i =0 ; $i<=2;$i++){
                    $arr_routes[$i] = $routes[$i];
                }

                $array['city_start'] = array_unique($arr['city_start']);
                $array['city_end'] = array_unique($arr['city_end']);
                $request->session()->flash('error', 'Không tim thấy tuyến xe hoặc nhà xe không có chuyến');
            }

        }
//        dd($request);
        $array = New Fluent($array);
        return view('applicant/book_ticket',[
            'city_start' => $array['city_start'],
            'city_end' => $array['city_end'],
            'request'=>$request,
            'routes'=>$arr_routes,
            'arr_bus'=>$arr_bus,
            'arr_route'=>$arr_route,
            'arr_location'=>$arr_location,
            'bus'=>$bus,
            'seatTypes'=>$seatTypes,
        ]);
    }

    public function payment(StoreInfoCustomerRequest $request)
    {
        $request->bus = json_decode($request->bus,true);
        $location = Location::query()->with('city')
            ->where('locations.id',$request->address_location)
            ->get();
        $location = $location[0];
        $address_location = $location->address .', ' . $location->district .', '. $location->city->name;
        $request->address_location_name = $address_location;
        $driver = Route_driver_car::query()->with('driver_name')
            ->where('route_driver_cars.id',$request->bus['route_driver_car_id'])
            ->get();
        $driver = $driver[0]->driver_name;
        $driver = $driver[0];
//        dd($driver-);
        $request->driver_name = $driver->name;
        $request->driver_phone = $driver->phone;
        $request->step = 4;
//        dd($request);
         return view('applicant/book_ticket',[
             'request'=>$request,
         ]);
    }

    public function order(OrderRequest $request)
    {
//        dd($request);
            $arr_customer = $request->arr_customer;
            $address = $arr_customer['address'] .', '.$arr_customer['district'] .', '.$arr_customer['city'];
            $arr_customer['address'] = $address;
            $arr_customer['birthday'] = $arr_customer['birthdate'];
            unset($arr_customer['district'],$arr_customer['city'],$arr_customer['birthdate']);
            $customer_id = Customer::firstOrCreate([
                'phone' => $arr_customer['phone']
            ], $arr_customer)->id;
            $object_customer = Customer::query()->find($customer_id);
            $object_customer -> fill($arr_customer);
            $object_customer->save();
//        bills
            $arr_bill['customer_id'] = $customer_id;
            $arr_bill['code'] = 'B'.strtoupper(Str::random(8));
            $arr_bill['price'] = $request->arr_bus['price'];
            $arr_bill['payment_method'] = PaymentMethodEnum::getValue(strtoupper($request->payment_method));
            $arr_bill['status'] = '0';
            $object_bill = Bill::query();
            $bill_id = $object_bill->create($arr_bill)->id;
//        bill_detail
            $arr_bill_detail['buses_id'] = $request->arr_bus['id'];
            $arr_bill_detail['bill_id'] = $bill_id;
            $arr_bill_detail['quantity'] = $request->arr_bus['quantity'];
            $arr_bill_detail['price'] = $request->arr_bus['price'];
            $object_bill_detail = Bill_detail::query();
            $bill_detail_id = $object_bill_detail->create($arr_bill_detail)->id;
//        dd($bill_detail_id);
//        tickets
            $arr_tickets['bill_detail_id'] = $bill_detail_id;
            $arr_tickets['code'] = 'T'.strtoupper(Str::random(8));
            $arr_tickets['name_passenger'] = $request->arr_customer['name'];
            $arr_tickets['phone_passenger'] = $request->arr_customer['phone'];
            $arr_tickets['email_passenger'] = $request->arr_customer['email'];
            $arr_tickets['address_passenger_id'] = $request->location;
            $object_ticket = Ticket::query();
            $object_ticket->create($arr_tickets);
//            dd($request);
            ApplicantOrderEvent::dispatch($request);
            return redirect()->route('index')->with('success', 'Bạn đã đặt vé thành công !!!');
    }

    public function schedule(){
        return view('applicant.schedule');
    }

    public function api_schedule(){
        $route_model = Route::query()->with('city_start')->with('city_end')->get()
            ->map(function($each){
                $arr_route_driver_car = Route_driver_car::query()->with('car_name')
                    ->select('car_id','route_id')
                    ->where('route_id',$each->id)
                    ->get()
                    ->map(function ($each1){
                        $each1->category_car = CarriageCategoryEnum::getKeyByValue(($each1->car_name->pluck('category'))[0]);
                        $each1->seat_type_car = SeatTypeEnum::getKeyByValue(($each1->car_name->pluck('seat_type'))[0]);
                        unset($each1->driver_name,$each1->car_name);
                        return $each1;
                    })->toArray();
                $arr_category_car = [];
                $arr_seat_type_car = [];
                foreach ($arr_route_driver_car as $key => $value){
                    $arr_category_car[$key]= $value['category_car'];
                    $arr_seat_type_car[$key]= $value['seat_type_car'];
                }
                $arr_category_car = array_unique($arr_category_car);
                $each->category_car = implode(', ', $arr_category_car);
                $arr_seat_type_car = array_unique($arr_seat_type_car);
                $each->seat_type_car = implode(', ', $arr_seat_type_car);
                return $each;
            });
        return DataTables::of($route_model)
            ->editColumn('name', function ($object) {
                return $object->name;
            })
            ->editColumn('city_start', function ($object) {
                return $object->city_start->pluck('name')->toArray()[0];
            })
            ->editColumn('city_end', function ($object) {
                return $object->city_end->pluck('name')->toArray()[0];
            })
            ->editColumn('distance', function ($object) {
                return $object->distance_name;
            })
            ->editColumn('time', function ($object) {
                return $object->time_name;
            })
            ->addColumn('show', function ($object) {
                $arr = [];
                $arr['city_start_id'] = $object->city_start->pluck('id')->toArray();
                $arr['city_end_id'] = $object->city_end->pluck('id')->toArray();
                $arr['date_today'] = date('d/m/Y');
                return $arr;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreTicketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTicketRequest  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
