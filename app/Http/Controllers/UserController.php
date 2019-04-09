<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $path = $request->thumbnail->storeAs('user_thumbnail',$request->thumbnail->getClientOriginalName());
       $user = new User;
       $user->name = $request->name; 
       $user->thumbnail = $path;
       $user->mobile = $request->mobile;
       $user->address = $request->address;
       $user->birthday = $request->birthday;
       $user->password = Hash::make($request->password);
       $user->email = $request->email; 
       $user->save();
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( UserUpdateRequest $request, $id)
    {
       $path = $request->thumbnail->storeAs('user_thumbnail',$request->thumbnail->getClientOriginalName());
       $user = User::find($id);
       $user->name = $request->name; 
       $user->thumbnail = $path;
       $user->mobile = $request->mobile;
       $user->address = $request->address;
       $user->birthday = $request->birthday;
       $user->password = Hash::make($request->password);
       $user->email = $request->email; 
       $user->save();
       $new_user = User::find($id);
        return $new_user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
    }

    public function getUsers(){
        $users = User::get();
        return datatables()->of($users)->addColumn('action',function( $users){
            return '
            <button  type="button" class="btn btn-info btn-show" data-id="'.$users->id.'"><i class="far fa-eye"></i></button>
            <button type="button" class="btn  btn-warning btn-edit" data-id="'.$users->id.'"><i class="far fa-edit"></i></button>
            <button type="button" class="btn btn-danger  btn-delete" data-id="'.$users->id.'"><i class="far fa-trash-alt"></i></button>';
        })
        ->editColumn('thumbnail',function($users){
        return '<img style="margin:auto; width:60px; height:60px;" src ="/storage/'.$users->thumbnail.'">';
        })
        ->editColumn('mobile',function($users){
            return  '<a href="tel:'.$users->mobile.'" ><i class="fas fa-phone-square"></i> &nbsp'.$users->mobile.'</a>';
        })
        ->rawColumns(['action','thumbnail','mobile'])
        ->toJson();
    }
}
