<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;


class AdminUserController extends Controller
{   
    function __construct(){
        $this->middleware(function(Request $request,$next){
            session(['module_active'=>'user']);

            return $next($request);
        });
    }

    function list(Request $request){

        $status = $request->input('status') ;
        if($status == 'trash'){
            $users = User::onlyTrashed()->paginate(10);
        }else{
            $keyword="";
            if($request->input('keyword')){
                $keyword = $request->input('keyword');
            }
            $users = User::where('name','LIKE',"%{$keyword}%")->paginate(10);
        }
        $count_user_active = User::count();
        $count_user_trash  = User::onlyTrashed()->count();

        $count = [$count_user_active,$count_user_trash];

        
        // $users = User::paginate(10);
        // dd($users->total());

        return view('admin.user.list',compact('users','count'));
    }
    function add(){
        

        return view('admin.user.add');
    }
    function store(Request $request){
        // if($request->input('btn-add')){
        //     return $request->input();
        // }
        $request->validate(
            [
                'name'=>'required|string|max:255',
                'email'=>'required|string|max:255|unique:users',
                'password'=>'required|string|min:8'
            ],
            [
                'required'=>':attribute khong duoc de trong',
                'min'=>':attribute co do dai it nhat :min ki tu',
                'max'=>':attribute co do dai nhieu nhat :max ki tu',
                // 'confirmed'=>'Xac nhan mat khau khong thanh cong'
            ],
            [
                'name'=>'Ten nguoi dung',
                'email'=>'Email',
                'password'=>'Mat khau'
            ]);

            User::create([
                'name' => $request->input('name') ,
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return redirect('admin/user/list')->with('status','Da them thanh vien thanh cong');

        
    }
    function delete($id){
        if(Auth::id()!=$id){
            $users=User::find($id);
            $users->delete();

            return redirect('admin/user/list')->with('status','Da xoa nguoi dung thanh cong');
        }else{
            return redirect('admin/user/list')->with('status','Ban khong the xoa chinh minh ra khoi he thong');
        }
        
    }

    function edit($id){
        $user = User::find($id);
        return view('admin.user.edit',compact('user'));
    }
    function update(Request $request , $id){
        $request->validate(
            [
                'name'=>'required|string|max:255',
                'password'=>'required|string|min:8'
            ],
            [
                'required'=>':attribute khong duoc de trong',
                'min'=>':attribute co do dai it nhat :min ki tu',
                'max'=>':attribute co do dai nhieu nhat :max ki tu',
                // 'confirmed'=>'Xac nhan mat khau khong thanh cong'
            ],
            [
                'name'=>'Ten nguoi dung',
                'password'=>'Mat khau'
            ]);
        User::where('id',$id)->update([
            'name' => $request->input('name') ,
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect('admin/user/list')->with('status','Da cap nhat thanh cong !!');
    }
}
