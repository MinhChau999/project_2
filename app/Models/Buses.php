<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Buses extends Model
{
    use HasFactory;

    public function route_driver_car()
    {
        return $this->belongsTo(Route_driver_car::class, 'route_driver_car_id');
    }

    public function get_first_day_of_week($number_of_week, $year)
    {
        Date::setWeekStartsAt(Carbon::SUNDAY);
        Date::setWeekEndsAt(Carbon::SATURDAY);
        $day = Carbon::createFromDate($year, 1, 1)->addWeeks($number_of_week-1)->startOfWeek();
        return $day;
    }

    public function get_last_day_of_week($number_of_week, $year)
    {
        Date::setWeekStartsAt(Carbon::SUNDAY);
        Date::setWeekEndsAt(Carbon::SATURDAY);
        $day = Carbon::createFromDate($year, 1, 1)->addWeeks($number_of_week-1)->endOfWeek();
        return $day;
    }
    
    // Check if the bus is available in the given time range
    public function check_available_carriage($route_id, $car_id, $departure_time){
        $time = Route::query()->find($route_id)->time;

        $timeMin = Carbon::parse($departure_time)->subHours($time)->format('y-m-d H:i:s');
        $timeMax = Carbon::parse($departure_time)->addHours($time)->format('y-m-d H:i:s');

        // check today // kiểm tra thời gian so với hôm nay
        $today = Carbon::now();
        $datetime = Carbon::parse($departure_time);
        if($datetime < $today){
            return false;
        }

        // check travel time // kiểm tra thời gian di chuyển
        $check_time = Buses::query()
            ->join('route_driver_cars', 'route_driver_cars.id', '=', 'buses.route_driver_car_id')
            ->where('route_driver_cars.car_id', $car_id)
            ->whereBetween('departure_time', [$timeMin, $timeMax]) 
            ->exists();
        if($check_time){
            return false; 
        }

        // check carriage reverse route // kiểm tra xe đang ở tuyến đường nào 
        // Nếu xe không hoạt động 1 ngày thì xe đó available
        $nearest_smaller_date = Buses::query()
            ->selectRaw('max(departure_time) as departure_time')
            ->join('route_driver_cars', 'route_driver_cars.id', '=', 'buses.route_driver_car_id')
            ->where('route_driver_cars.car_id', $car_id)
            ->where('departure_time', '<', $departure_time->format('y-m-d H:i:s'));
        $nearest_bigger_date = Buses::query()
            ->selectRaw('max(departure_time) as departure_time')
            ->join('route_driver_cars', 'route_driver_cars.id', '=', 'buses.route_driver_car_id')
            ->where('route_driver_cars.car_id', $car_id)
            ->where('departure_time', '>', $departure_time->format('y-m-d H:i:s'));
        $route_id_nearest_date = Buses::query()
        ->join('route_driver_cars', 'route_driver_cars.id', '=', 'buses.route_driver_car_id')
        ->where('route_driver_cars.car_id', $car_id)
        ->where('departure_time', $nearest_smaller_date->first()->departure_time)
        ->Orwhere('departure_time', $nearest_bigger_date->first()->departure_time)
        // Thêm điều kiện 1 ngày không hoạt động
        ->get()->pluck('route_id')->toArray();
        if(in_array($route_id, $route_id_nearest_date)){
            return false;
        }
        return true;
    }

    public $timestamps = false;

    protected $fillable = [
        "route_driver_car_id",
        "departure_time",
        "price",
    ];
}
