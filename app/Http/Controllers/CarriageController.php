<?php

namespace App\Http\Controllers;

use App\Enums\CarriageCategoryEnum;
use App\Enums\SeatTypeEnum;
use App\Models\Carriage;
use App\Http\Requests\StoreCarriageRequest;
use App\Http\Requests\UpdateCarriageRequest;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class CarriageController extends Controller
{
    use ResponseTrait;
    private object $model;
    private string $table;

    public function __construct()
    {
        $this->model = (new Carriage())->query();
        $this->table = (new Carriage())->getTable();

        // Làm sidebar - để biết mình đag ở tab nào
        $current_path = Route::getFacadeRoot()->current()->uri();
        $current_path_1 = Str::after($current_path, 'admin/');
        $route = Str::before($current_path_1, '/');

        // Load seat type enum
        $seatTypes = SeatTypeEnum::asArray();
        // Load category enum
        $categories = CarriageCategoryEnum::asArray();

        View::share([
            'title' => ucwords($this->table),
            'route' => $route,
            'seatTypes' => $seatTypes,
            'categories' => $categories,
        ]);
    }

    public function api()
    {
        return DataTables::of($this->model)
            ->editColumn('seat_type', function ($model) {
                return SeatTypeEnum::getKeyByValue($model->seat_type);
            })
            ->editColumn('category', function ($model) {
                return CarriageCategoryEnum::getKeyByValue($model->category);
            })
            ->addColumn('edit', function ($object) {
                return route('admin.carriages.edit', $object);
            })
            ->addColumn('delete', function ($object) {
                return route('admin.carriages.destroy', $object);
            })
            ->make(true);
    }

    public function apiNameCarriages(Request $request)
    {
        return $this->model->where('license_plate', 'like', '%' . $request->get('q') . '%')->get();
    }

    public function apiNumberSeats(Request $request)
    {
        return $this->model->where('default_number_seat', 'like', '%' . $request->get('q') . '%')->distinct()->orderBy('default_number_seat', 'desc')->get('default_number_seat');
    }

    public function index()
    {
        $breadcumbs = Breadcrumbs::render('carriage');
        return view('admin.carriage.index', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function create()
    {
        $breadcumbs = Breadcrumbs::render('carriage.create');
        return view('admin.carriage.create', [
            'breadcumbs' => $breadcumbs,
        ]);
    }

    public function store(StoreCarriageRequest $request)
    {
        try {
            $this->model->create($request->except('_token'));
            return redirect()->route('admin.carriages.index')->with('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.carriages.index')->with('error', 'Thêm mới thất bại');
        }
    }

    public function edit(Carriage $carriage)
    {
        $breadcumbs = Breadcrumbs::render('carriage.edit', $carriage);
        return view('admin.carriage.edit', [
            'carriage' => $carriage,
            'breadcumbs' => $breadcumbs,
        ]);
    }


    public function update(UpdateCarriageRequest $request, Carriage $carriage)
    {
        try {
            $carriage->update($request->except('_token'));
            return redirect()->route('admin.carriages.index')->with('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            return redirect()->route('admin.carriages.index')->with('error', 'Cập nhật thất bại');
        }
    }

    public function destroy($carriage)
    {
        try {
            Carriage::destroy($carriage);
            return response()->json([
                'heading' => 'success',
                'text' => 'Xóa thành công',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'heading' => 'error',
                'text' => 'Xóa thất bại',
                'icon' => 'error',
            ]);
        }
    }
}
