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
use App\DetailOrder;
use App\Memory;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
         * Tác dụng :Khởi tạo
         *
         * @param  name space
         * @param  int   biến chuyền vào
         * @return \Illuminate\Http\Response trả về gì
         */
    public function __construct()
    {
        $this->middleware('permission:show_product', ['only' => ['index', 'show']]);
        $this->middleware('permission:add_product', ['only' => ['store']]);
        $this->middleware('permission:update_product', ['only' => [ 'edit', 'update']]);
        $this->middleware('permission:delete_product', ['only' => ['destroy']]);
    }
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
         $memories = Memory::get();
        return view('product.index',['users'=>$users,'categories'=>$categories,'brands'=>$brands, 'colors'=>$colors,'branches'=>$branches, 'memories'=>$memories]);
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
       $detail_product = DetailProduct::where('product_id',$id)->get();
       $exists= array();
       foreach ($detail_product as $key => $value) {
           $exists = DetailOrder::where('detail_product_id',$value->id)->get();
       }
        if($exists!=null){
            return response()->json([
                'error'=>true,
                'message'=>'Không thể xóa vì hiện tại sản phẩm đang được order !',
            ]);
        }
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
        $data='';
        if(Auth::user()->can('show_product')){
            $data.='<button type="button" title="Xem chi tiết" class="btn btn-info btn-show" data-id="'.$products->id.'"><i class="far fa-eye"></i></button>';
        }
        if(Auth::user()->can('show_review')){
            $data.='<button type="button" title="Reviews sản phẩm" class="btn btn-secondary btn-reviews" data-id="'.$products->id.'"><i class="far fa-file"></i></button>';
        }
         if(Auth::user()->can('update_product')){
            $data.='
            <button type="button" title="Thêm ảnh sản phẩm" class="btn btn-success btn-images" data-id="'.$products->id.'"><i class="far fa-images"></i></button>
            <button type="button" title="Chỉnh sửa thông tin" class="btn  btn-warning btn-edit" data-id="'.$products->id.'"><i class="far fa-edit"></i></button>';
         }
         if (Auth::user()->can('delete_product')) {
             $data.='<button type="button"  title="Xóa sản phẩm" class="btn btn-danger  btn-delete" data-id="'.$products->id.'"><i class="far fa-trash-alt"></i></button>';
         }
        
        return $data;
        })
       ->editColumn('product_details',function($products){
                if(Auth::user()->can('show_detail_product')){
                    return ' <button type="button" title="Danh sách chi tiết dản phẩm" class="btn btn-default btn-list_detail_products" data-id="'.$products->id.'"><i class="fas fa-list-ol"></i></button>';
                }
                else{
                    return 'Không có quyền hạn trên tác vụ';
                }
                
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

    public function detailProductSlug($slug)
    {
        $product = Product::join('brands', 'brands.id', '=', 'products.brand_id')
        ->select('products.*', 'brands.name as brand_name', 'brands.origin as origin')
        ->where('products.slug',$slug)->get()->first();

        $product->detail_product = DB::table('memories as m')
        ->join('detail_products as dp', 'm.id', '=', 'dp.memory')
        ->select('dp.*', 'm.name as memory_name', 'm.code as memory_code')
        ->where('dp.product_id',$product->id)
        ->groupBy('m.id')
        ->get();
        
        // dd($product->detail_product);
        

        $product->images = DB::table('images')->where('product_id',$product->id)->get();
        // $product->image_first = DB::table('images')->where('product_id',$product->id)->get()->first()->thumbnail;
        $product->reviews = DB::table('reviews')->where('product_id',$product->id)->get();

        
        $product_categories = DB::table('brands as b')
        ->join('products as p','p.brand_id','=','b.id')
        ->join('categories as c', 'c.id', '=', 'p.category_id')
        ->select('p.*', 'b.name as brand_name' , 'c.name as category_name')
        ->where('p.category_id',$product->category_id)
        ->get();
        
        foreach ($product_categories as $key => $value) {
             $value->thumbnail = DB::table('images')->where('product_id',$value->id)->first();
        }
        
        
        return view('shop.detailproduct',['product'=>$product,'product_categories'=>$product_categories]);
    }
    //  đây là ddeer đổ lên trang cho người dùng toàn bộ những sản phẩm
    public function productList()
    {
         $products = DB::table('brands as b')
        ->join('products as p','p.brand_id','=','b.id')
        ->join('categories as c', 'c.id', '=', 'p.category_id')
        ->select('p.*', 'b.name as brand_name' , 'c.name as category_name')
        ->paginate(9);
        foreach ($products as $key => $value) {

             $value->thumbnail = DB::table('images')->where('product_id',$value->id)->first();
             $value->detail = DB::table('detail_products')->where('product_id',$value->id)->first();
        }
        
        // dd($products);
        // 
        return view('shop.product',['products'=>$products]);
        
    }
    public function productCategory($slug){
        $products = DB::table('brands as b')
        ->join('products as p','p.brand_id','=','b.id')
        ->join('categories as c', 'c.id', '=', 'p.category_id')
        ->select('p.*', 'b.name as brand_name' , 'c.name as category_name')
        ->where('c.slug', $slug)
        ->paginate(9);
        foreach ($products as $key => $value) {
             $value->thumbnail = DB::table('images')->where('product_id',$value->id)->first();
             $value->detail = DB::table('detail_products')->where('product_id',$value->id)->first();
        }
        
        // dd($products);
         return view('shop.product_category',['products'=>$products]);
    }

    public function getProductSale(){
        $products = DB::table('brands as b')
        ->join('products as p','p.brand_id','=','b.id')
        ->join('categories as c', 'c.id', '=', 'p.category_id')
        ->select('p.*', 'b.name as brand_name' , 'c.name as category_name')
        ->get();
        return datatables()->of($products)
       ->editColumn('choose',function($products){
               
                    return ' <button type="button" title="Danh sách chi tiết dản phẩm" class="btn btn-default btn-list_detail_products" data-id="'.$products->id.'"><i class="fas fa-list-ol"></i></button>';
 
         })
        ->editColumn('brand_id',function($products){
                return ''.$products->brand_name.'';
                })
        ->editColumn('category_id',function($products){
                return ''.$products->category_name.'';
                })
        ->rawColumns(['brand_id','category_id','choose'])
        ->toJson();

    }
     // lấy ra các chi tiết sản phẩm có slug và memory truyền vào
    public function getDetailProductSlugMemory($slug,$memory)
    {
        $detail_product = DB::table('products as p')
        ->join('detail_products as dp', 'dp.product_id', '=', 'p.id')
        ->join('memories as m', 'm.id', '=', 'dp.memory')
        ->join('colors as c', 'c.id', '=', 'dp.color_id')
        ->where('m.id',$memory)
        ->where('p.slug',$slug)
        ->select('c.name as color_name', 'dp.*')
        ->get();

        return $detail_product;
    }

}
