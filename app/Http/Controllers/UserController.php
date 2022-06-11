<?php

namespace App\Http\Controllers;

use App\Enums\UserLevelEnum;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private object $model;
    private string $table;

    public function __construct(){
        $this->model = User::query();
        $this->table = (New User())->getTable();
        $route = Str::afterLast(Route::getFacadeRoot()->current()->uri(), '/');
        View::share([
            'title'=> ucwords($this->table),
            'route'=>$route,
        ]);
    }

    public function index()
    {
        $level_user = Auth::user()->level;
        $user = UserLevelEnum::getKeyByValue($level_user);
        $title ='Chào ' . $user .', chúc bạn một ngày tốt lành !!!';
        return view('admin.index',[
            'title' => NULL,
            'title_index' =>$title,
        ]);
    }

    public function api()
    {
        return DataTables::of($this->model)
            ->editColumn('gender', function ($object) {
                return $object->gender_name;
            })
            ->editColumn('birthdate', function ($object) {
                return $object->date_VN;
            })
            ->editColumn('address', function ($object) {
                return $object->provinces;
            })
            ->editColumn('level', function ($object) {
                return UserLevelEnum::getKeyByValue($object->level);
            })
            ->editColumn('name', function ($object) {
                return [
                    'name' => $object->name,
                    'src'  => $object->src_image_level,
                ];
            })
            ->addColumn('destroy', function ($object) {
                return route('admin.users.destroy', $object);
            })
            ->filterColumn('level', function($query, $keyword) {
                if($keyword !=='-1'){
                    $query->where('level',$keyword);
                }
            })
            ->filterColumn('name', function($query, $keyword) {
                if($keyword !=='null'){
                    $query->where('name',$keyword);
                }
            })
            ->make(true);
    }

    public function apiNameUsers(Request $request)
    {
        return $this->model->where('name','like','%'.$request->get('q') .'%')->get();
    }

    public function apiProvinces(Request $request)
    {
        return $this->model->where('address','like','%'.$request->get('q') .'%')->get();
    }

    public function show_users()
    {
        $levels_us = UserLevelEnum::asArray();
        $levels=[];
        foreach ($levels_us as $level=>$value) {
            $level = UserLevelEnum::getKeyByValue($value);
            $levels[$level]=$value;
        }

        return view('admin.users',[
            'levels' => $levels,
        ]);
    }

    public function create()
    {
        $levels_us = UserLevelEnum::asArray();
        $levels=[];
        foreach ($levels_us as $level=>$value) {
            if($value !== 2){
                $level = UserLevelEnum::getKeyByValue($value);
                $levels[$level]=$value;
            }
        }
        return view('admin.create',[
            'levels' => $levels,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $arr=[];
        $arr['status'] = true;
        $arr['messages'] ='';
        $user_get = $this->model->where('id',$user)->firstOrFail();
        if($user_get->level == 2){
            $arr['status'] = false;
        }
        else{
            User::destroy($user);
        }
        return response($arr,200);
    }
}
