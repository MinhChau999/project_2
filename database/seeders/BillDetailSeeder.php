<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Bill_detail;
use App\Models\Buses;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class BillDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [];
        // $seat = Seat::query()->pluck('id')->toArray();
        $buses = Buses::query()->pluck('id')->toArray();
        $bill = Bill::query()->pluck('id')->toArray();
        $faker = \Faker\Factory::create('vi_VN');
        for ($i = 1; $i <= 100; $i++) {
            $arr[] = [
                // 'seat_id' => $faker->randomElement($seat),
                'buses_id' => $faker->randomElement($buses),
                'bill_id' => $faker->randomElement($bill),
                'quantity' => $faker->numberBetween(1, 10),
                'price' => $faker->numberBetween(80000, 1000000),
            ];
        }
        Bill_detail::insert($arr);
    }
}
