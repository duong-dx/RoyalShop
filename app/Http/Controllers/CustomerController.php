<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer.index');
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
    public function store(CustomerStoreRequest $request)
    {
        $path = $request->thumbnail->storeAs('customer_thumbnail',$request->thumbnail->getClientOriginalName());
       $customer = new Customer;
       $customer->name = $request->name;
       $customer->thumbnail = $path;
       $customer->mobile = $request->mobile;
       $customer->address = $request->address;
       $customer->birthday = $request->birthday;
       $customer->password = Hash::make($request->password);
       $customer->email = $request->email; 
       $customer->save();
      
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer=Customer::find($id);
        return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer=Customer::find($id);
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, $id)
    {
        $path = $request->thumbnail->storeAs('customer_thumbnail',$request->thumbnail->getClientOriginalName());
       $customer = Customer::find($id);
       $customer->name = $request->name; 
       $customer->thumbnail = $path;
       $customer->mobile = $request->mobile;
       $customer->address = $request->address;
       $customer->birthday = $request->birthday;
       $customer->password = Hash::make($request->password);
       $customer->email = $request->email; 
       $customer->save();
       $new_customer = Customer::find($id);
        return $new_customer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->delete();
    }

    public function getCustomers()
    {
        $customers = Customer::get();
        return datatables()->of($customers)->addColumn('action',function( $customers){
            return '
            <button  type="button" class="btn btn-info btn-show" data-id="'.$customers->id.'"><i class="far fa-eye"></i></button>
            <button type="button" class="btn  btn-warning btn-edit" data-id="'.$customers->id.'"><i class="far fa-edit"></i></button>
            <button type="button" class="btn btn-danger  btn-delete" data-id="'.$customers->id.'"><i class="far fa-trash-alt"></i></button>';
        })
        ->editColumn('thumbnail',function($customers){
        return '<img style="margin:auto; width:60px; height:60px;" src ="/storage/'.$customers->thumbnail.'">';
        })
        ->editColumn('mobile',function($customers){
            return  '<a href="tel:'.$customers->mobile.'" ><i class="fas fa-phone-square"></i> &nbsp'.$customers->mobile.'</a>';
        })
        ->rawColumns(['action','thumbnail','mobile'])
        ->toJson();
    }
}
