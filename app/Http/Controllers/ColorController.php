<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Http\Request;
use App\Http\Requests\ColorRequest;
class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('color.index');
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
    public function store(ColorRequest $request)
    {
         $color = Color::create($request->all());
        return $color;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $color = Color::find($id);
        return $color;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(ColorRequest $request, $id)
    {
        $color = Color::find($id)->update($request->all());
        $new_color = Color::find($id);
        return $new_color;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Color::find($id)->delete();
    }
    public function getColors()
    {
        $colors= Color::all();
        return datatables()->of($colors)
                           ->addColumn('action', function($colors){
        return '
        <button type="button" class="btn  btn-warning btn-edit" data-id="'.$colors->id.'"><i class="far fa-edit"></i></button>
        <button type="button" class="btn btn-danger  btn-delete" data-id="'.$colors->id.'"><i class="far fa-trash-alt"></i></button>';
        })
       ->editColumn('code',function($colors){
        return '<div style="width:30px; height:30px; background:'.$colors->code.'; margin:auto;"></div>';
        })
        ->rawColumns(['action','code'])
        ->toJson();
    }
}
