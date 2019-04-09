<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use App\Category;
use App\Brand;
use App\Image;
use App\Color;
use App\Branch;
use App\DetailProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $users = User::get();
         $categories = Category::get();
         $brands = Brand::get();
         $colors = Color::get();
         $branches = Branch::get();
        return view('product.index',['users'=>$users,'categories'=>$categories,'brands'=>$brands, 'colors'=>$colors,'branches'=>$branches]);
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
    public function store(ProductStoreRequest $request)
    {
       
        $product=Product::create( $request->all());
        return $product;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::find($id);
        $images=Image::where('product_id',$id)->get();
        $user=User::find($product->user_id);
        $brand=Brand::find($product->brand_id);
        $category=Category::find($product->category_id);
        return response()->json(['product'=>$product,'images'=>$images,'user'=>$user,'brand'=>$brand,'category'=>$category]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product=Product::find($id);
       return $product;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::find($id)->update($request->all());
        $new_product =Product::find($id);
        return $new_product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        Image::where('product_id',$id)->delete();
        DetailProduct::where('product_id',$id)->delete();
    }

    public function addImages(Request $request){
        $image = $request->file('file');
        foreach ($image as $key => $value) {
            $imageName[]=$image[$key]->getClientOriginalName();
            $path =$value->storeAs('product_thumbnail',$image[$key]->getClientOriginalName());
            $imageUpload =  new Image;
            $imageUpload->thumbnail=$path;
            $imageUpload->product_id =$request->product_id;
            $imageUpload->save();
       }

    }
    public function getProducts()
    {
        $products = DB::table('brands as b')
        ->join('products as p','p.brand_id','=','b.id')
        ->join('categories as c', 'c.id', '=', 'p.category_id')
        ->select('p.*', 'b.name as brand_name' , 'c.name as category_name')
        ->get();
        
        
       return datatables()->of($products)->addColumn('action',function( $products){
            return'
            
            <button type="button" title="Thêm ảnh sản phẩm" class="btn btn-success btn-images" data-id="'.$products->id.'"><i class="far fa-images"></i></button>
            <button type="button" title="Reviews sản phẩm" class="btn btn-secondary btn-reviews" data-id="'.$products->id.'"><i class="far fa-file"></i></button>
            <button type="button" title="Xem chi tiết" class="btn btn-info btn-show" data-id="'.$products->id.'"><i class="far fa-eye"></i></button>
        <button type="button" title="Chỉnh sửa thông tin" class="btn  btn-warning btn-edit" data-id="'.$products->id.'"><i class="far fa-edit"></i></button>
        <button type="button"  title="Xóa sản phẩm" class="btn btn-danger  btn-delete" data-id="'.$products->id.'"><i class="far fa-trash-alt"></i></button>';
        })
       ->editColumn('product_details',function($products){
                return ' <button type="button" title="Danh sách chi tiết dản phẩm" class="btn btn-default btn-list_detail_products" data-id="'.$products->id.'"><i class="fas fa-list-ol"></i></button>';
                })
        ->editColumn('brand_id',function($products){
                return ''.$products->brand_name.'';
                })
        ->editColumn('category_id',function($products){
                return ''.$products->category_name.'';
                })
        ->rawColumns(['action','brand_id','category_id','product_details'])
        ->toJson();
    }
}
