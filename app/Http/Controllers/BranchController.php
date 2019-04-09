<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\BranchRequest;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('branch.index');
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
    public function store(BranchRequest $request)
    {
        $branch = Branch::create($request->all());
        return $branch;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $branch = Branch::find($id);
       return $branch;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        $branch = Branch::find($id)->update($request->all());
        $new_branch = Branch::find($id);
        return $new_branch;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Branch::find($id)->delete();
    }
    public function getBranches(){
        $branches = Branch::get();
        return datatables()->of($branches)->addColumn('action',function( $branches){
            return '
            
            <button type="button" class="btn  btn-warning btn-edit" data-id="'.$branches->id.'"><i class="far fa-edit"></i></button>
            <button type="button" class="btn btn-danger  btn-delete" data-id="'.$branches->id.'"><i class="far fa-trash-alt"></i></button>';
        })
        ->editColumn('mobile',function($branches){
            return  '<a href="tel:'.$branches->mobile.'" ><i class="fas fa-phone-square"></i> &nbsp'.$branches->mobile.'</a>';
        })
        ->rawColumns(['action', 'mobile'])
        ->toJson();
    }
}
