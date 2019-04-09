<?php

namespace App\Http\Controllers;

use App\DetailProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\DetailProductStoreRequest;

class DetailProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(DetailProductStoreRequest $request)
    {
       $detail_product_add=DetailProduct::create($request->all());
       return $detail_product_add;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetailProduct  $detailProduct
     * @return \Illuminate\Http\Response
     */
    public function show(DetailProduct $detailProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetailProduct  $detailProduct
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $detail_product = DetailProduct::find($id);
        return $detail_product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetailProduct  $detailProduct
     * @return \Illuminate\Http\Response
     */
    public function update(DetailProductStoreRequest $request, $id)
    {
        $detail_product = DetailProduct::find($id)->update($request->all());
        $new_detail_product =DetailProduct::find($id);
        return $new_detail_product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetailProduct  $detailProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DetailProduct::find($id)->delete();
    }
    public function getDetailProducts($id){
        $detail_products = DB::table('branches as b')
        ->join('detail_products as dp', 'dp.branch_id', '=', 'b.id')
        ->join('colors as c', 'dp.color_id', '=', 'c.id')
        ->join('products as p', 'dp.product_id', '=', 'p.id')
        ->where('dp.product_id', $id)
        ->select('dp.*', 'b.name as branch_name', 'c.name as color_name','p.name as product_name')
        ->get();
        
        if($detail_products!=null){
             return datatables()->of($detail_products)
             ->editColumn('product_name',function($detail_products){
                return ''.$detail_products->product_name.'';
                })
             ->editColumn('color_name',function($detail_products){
                return ''.$detail_products->color_name.'';
                })
             ->editColumn('sale_price',function($detail_products){
                return ''.number_format($detail_products->sale_price).'';
                })
            ->addColumn('action',function($detail_products){
                return'
                
            <button type="button" title="Chỉnh sửa thông tin" class="btn  btn-warning btn-edit-detail-product" data-id="'.$detail_products->id.'"><i class="far fa-edit"></i></button>
            <button type="button"  title="Xóa sản phẩm" class="btn btn-danger  btn-delete-detail-product" data-id="'.$detail_products->id.'"><i class="far fa-trash-alt"></i></button>';
            })
            ->rawColumns(['product_name', 'action', 'color_name'])
            ->toJson();
            
        }
    }
}
