@extends('layouts.master')
@section('css')
<style type="text/css">
	.dropzone {
		border: 2px dashed #0087F7;
		border-radius: 5px;
		background: white;
		width: 80%; 
		margin:2% auto;
		

	}
</style>
@endsection
@section('content')

<div style="font-size: 15px !important;" class="container">
	<a style="margin: 5% 0% 2% 0%; " href="javascript:;" class="btn btn-dark btn-add">
		Add product
	</a>
	
	<div class="table-responsive">
		<table style="text-align: center;" class="table table-bordered" id="products-table">
			<thead >
				<tr>
					<th>Name</th>
					<th>Product details</th>
					<th>Brand name</th>
					<th>Category name</th>
					<th>Action</th>
				</tr>
			</thead>
		</table>
		<div class="clear"></div>
	</div>
	{{-- Modal show chi tiết category --}}
	<div  class="modal fade" id="modal-product_details">
		<div style="width: 80%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Show category</h4>
				</div>
				<div class="modal-body" >

					<div style="width:90%;font-size: 15px;   margin: 3% auto 3%;">

						<table style="width:100%; margin: 1% auto 3%; " id="option_values" class="table">
							<thead >
								<tr>
									<th>Id</th>
									<th>Option Name</th>
									<th>Code</th>
									<th>Value</th>

								</tr>
							</thead>	
						</table>

					</div>


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	{{-- modal add  --}}
	<div class="modal fade" id="modal-add">
		<div style="width: 80%;" class="modal-dialog">
			<div class="modal-content">


				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add Product</h4>

				</div>
				
				<form action="" id="form-add" method="post" role="form" enctype="multipart/form-data">
					@csrf
					<div style="margin:auto; " class="modal-body">
						<div style="float: left; width:49%; margin:auto; ">
							<h4 style="margin-bottom: 5%;">Thông tin :</h4>
							<div class="form-group">
								<label for="">* Name</label>
								<input type="text" class="form-control" id="name_add"  name ="name" placeholder="Name">
								<span id="span_name_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Slug</label>
								<input type="text" class="form-control" id="slug_add"  name ="slug" placeholder="Slug">
								<span id="span_slug_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Code</label>
								<input type="text" class="form-control" id="code_add"  name ="code" placeholder="Code">
								<span id="span_code_add"></span>
							</div>
							<div class="form-group">
								<label for="">* User </label>
								
								<select class="form-control" id="user_id_add"  name ="user_id">
									@foreach($users as $user)
									<option value="{{ $user->id }}">{{ $user->name }}</option>
									@endforeach
								</select>
								
								<span id="span_user_id_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Category </label>
								
								<select class="form-control" id="category_id_add"  name ="category_id">
									@foreach($categories as $category)
									<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
								
								<span id="span_category_id_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Brand </label>
								
								<select class="form-control" id="brand_id_add"  name ="brand_id">
									@foreach($brands as $brand)
									<option value="{{ $brand->id }}">{{ $brand->name }}</option>
									@endforeach
								</select>
								
								<span id="span_brand_id_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Warranty Time (Month)</label>
								<input type="text" class="form-control" id="warranty_time_add"  name ="warranty_time" placeholder="Warranty Time">
								<span id="span_warranty_time_add"></span>
							</div>
							
						</div>
						<div style="float: left; width:49%; margin-left: 2%;">
							<h4 style="margin-bottom: 5%;  ">Thông số kĩ thuật :</h4>
							<div class="form-group">
								<label for="">* Ram (GB)</label>
								<input type="text" class="form-control" id="ram_add"  name ="ram" placeholder="Ram">
								<span id="span_ram_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Weight (Gram)</label>
								<input type="text" class="form-control" id="weight_add"  name ="weight" placeholder="Weight">
								<span id="span_weight_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Screen Size (inch)</label>
								<input type="text" class="form-control" id="screen_size_add"  name ="screen_size" placeholder="Screen Size">
								<span id="span_screen_size_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Pin (mAh)</label>
								<input type="text" class="form-control" id="pin_add"  name ="pin" placeholder="Pin">
								<span id="span_pin_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Front Camera (Megapixel)</label>
								<input type="text" class="form-control" id="front_camera_add"  name ="front_camera" placeholder="Front Camera">
								<span id="span_front_camera_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Rear Camera (Megapixel)</label>
								<input type="text" class="form-control" id="rear_camera_add"  name ="rear_camera" placeholder="Rear Camera">
								<span id="span_rear_camera_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Operating System (Example: IOS 12.4)</label>
								<input type="text" class="form-control" id="operating_system_add"  name ="operating_system" placeholder="Operating System">
								<span id="span_operating_system_add"></span>
							</div>

						</div>

					</div>
					<div class="clear"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit"  class="btn btn-primary">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	{{-- modal update  --}}
	<div class="modal fade" id="modal-update">
		<div style="width: 80%;" class="modal-dialog">
			<div class="modal-content">

				<form action="" id="form-update" method="category" role="form">
					@csrf
					<input type="hidden" id="put" name="_method" value="put">
					<input type="hidden" readonly name="id" id="id_update">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Edit Product</h4>
					</div>
					
					<div class="modal-body">

							<div style="float: left; width:49%; margin:auto; ">
								<h4 style="margin-bottom: 5%;">Thông tin :</h4>
								<div class="form-group">
									<label for="">* Name</label>
									<input type="text" class="form-control" id="name_update"  name ="name" placeholder="Name">
									<span id="span_name_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Slug</label>
									<input type="text" class="form-control" id="slug_update"  name ="slug" placeholder="Slug">
									<span id="span_slug_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Code</label>
									<input type="text" class="form-control" id="code_update"  name ="code" placeholder="Code">
									<span id="span_code_update"></span>
								</div>
								<div class="form-group">
									<label for="">* User </label>
									
									<select class="form-control" id="user_id_update"  name ="user_id">
										@foreach($users as $user)
										<option value="{{ $user->id }}">{{ $user->name }}</option>
										@endforeach
									</select>
									
									<span id="span_user_id_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Category </label>
									
									<select class="form-control" id="category_id_update"  name ="category_id">
										@foreach($categories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
										@endforeach
									</select>
									
									<span id="span_category_id_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Brand </label>
									
									<select class="form-control" id="brand_id_update"  name ="brand_id">
										@foreach($brands as $brand)
										<option value="{{ $brand->id }}">{{ $brand->name }}</option>
										@endforeach
									</select>
									
									<span id="span_brand_id_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Warranty Time (Month)</label>
									<input type="text" class="form-control" id="warranty_time_update"  name ="warranty_time" placeholder="Warranty Time">
									<span id="span_warranty_time_update"></span>
								</div>
								
							</div>
							<div style="float: left; width:49%; margin-left: 2%;">
								<h4 style="margin-bottom: 5%;  ">Thông số kĩ thuật :</h4>
								<div class="form-group">
									<label for="">* Ram (GB)</label>
									<input type="text" class="form-control" id="ram_update"  name ="ram" placeholder="Ram">
									<span id="span_ram_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Weight (Gram)</label>
									<input type="text" class="form-control" id="weight_update"  name ="weight" placeholder="Weight">
									<span id="span_weight_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Screen Size (inch)</label>
									<input type="text" class="form-control" id="screen_size_update"  name ="screen_size" placeholder="Screen Size">
									<span id="span_screen_size_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Pin (mAh)</label>
									<input type="text" class="form-control" id="pin_update"  name ="pin" placeholder="Pin">
									<span id="span_pin_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Front Camera (Megapixel)</label>
									<input type="text" class="form-control" id="front_camera_update"  name ="front_camera" placeholder="Front Camera">
									<span id="span_front_camera_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Rear Camera (Megapixel)</label>
									<input type="text" class="form-control" id="rear_camera_update"  name ="rear_camera" placeholder="Rear Camera">
									<span id="span_rear_camera_update"></span>
								</div>
								<div class="form-group">
									<label for="">* Operating System (Example: IOS 12.4)</label>
									<input type="text" class="form-control" id="operating_system_update"  name ="operating_system" placeholder="Operating System">
									<span id="span_operating_system_update"></span>
								</div>

							</div>

					

					</div>
					<div class="clear"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit"  class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- modal show  --}}
	<div class="modal fade" id="modal-show">
		<div style="width:80%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom: 0px;">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4   >Thông tin sản phẩm :</h4>
					

				</div>
				<div style="margin:auto; width:60%;" id="images-div">
					
				</div>
				<div style="font-size: 15px; margin:  auto; text-align: left;">
							<table  style="text-align: left;margin:auto; width:80%; " class="table">

								
								
								<tr>
									<td>Name :</td>
									<td><p id="product_name"></p></td>
									<td>Code :</td>
				                  <td><p id="product_code"></p></td>
								</tr>
				                
				                <tr>
				                  <td>Slug :</td>
				                  <td><p id="product_slug"></p></td>
				                   <td>User name :</td>
				                  <td><p id="product_user_id"></p></td>
				                </tr>
				               
								<tr>
									<td>Category name :</td>
									<td><p id="product_category_id"></p></td>
									<td>Brand name :</td>
									<td><p id="product_brand_id"></p></td>
								</tr>
								
								<tr>
									<td>Warranty time :</td>
									<td><p id="product_warranty_time"></p></td>
									<td>Ram :</td>
									<td><p id="product_ram"></p></td>
								</tr>
								
								<tr>
									<td>Weight :</td>
									<td><p id="product_weight"></p></td>
									<td>Screen size :</td>
									<td><p id="product_screen_size"></p></td>
								</tr>
								
								<tr>
									<td>Pin :</td>
									<td><p id="product_pin"></p></td>
									<td>Front Camera :</td>
									<td><p id="product_front_camera"></p></td>
								</tr>
								
								<tr>
									<td>Rear Camera :</td>
									<td><p id="product_rear_camera"></p></td>
									<td>Operating System :</td>
									<td><p id="product_operating_system"></p></td>
								</tr>
								
								
							
							</table>

							
							
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						
					</div>
			</div>
		</div>
	</div>
{{-- modal images --}}
<div class="modal fade" id="modal-images">
		<div style="width:80%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom: 0px;">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4   >Ảnh sản phẩm :</h4>
					

				</div>
				<div style="margin-bottom:10%;" style="margin:auto; width:60%; text-align: center;" id="list_images">
					
				</div>
				<div class="clear"></div>

				<div style="text-align: center; margin: 5%;">
					<h5 >Thêm ảnh sản phẩm :</h5>
							<form action="/admin/addImages" class="dropzone" id="myDropzone">
						      @csrf
						      <input type="hidden" readonly name="product_id" id="product_id_add_images">
						      <div class="fallback">
						        <input name="file" type="file" multiple />
						      </div>
						    </form>
							
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit"  id="save_images" class="btn btn-primary">Save</button>
					</div>
			</div>
		</div>
	</div>

{{-- modal detail_products --}}
<div class="modal fade" id="modal-detail_products">
		<div style="width:80%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom: 0px;">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4  >Chi tiết sản phẩm :</h4>
					

				</div>
				<a style="margin-left: 5%;margin-top: 3%; " id="add_product_detal" href="javascript:;" class="btn btn-success">
						Add Details
					</a>
				<div class="modal-body">
					
					<div style="margin: auto; text-align: center; width: 90%;">
						<table class="table" id="detail_products-table">
							<thead >
								<tr>
									<th>Product name</th>
									<th>Memory</th>
									<th>Color name</th>
									<th>Price Sale</th>
									<th>Quantity</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

				</div>
				<div class="clear"></div>
				<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						
				</div>
			</div>
		</div>
	</div>
	{{-- modal detail_products --}}
<div class="modal fade" id="modal-add_detail_products">
		<div style="width: 70%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom: 0px;">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4  >Thêm chi tiết sản phẩm sản phẩm :</h4>
					

				</div>
				<form id="form_add_detail_product" method="POST" role="form">
					@csrf
				<div class="modal-body">
					
					<div style="margin-left:2%; float: left;  width: 45%;">	

							<input type="hidden" name="product_id" readonly class="form-control" id="product_id_add">
							<div class="form-group">
								<label for="">* Color</label>
								<select id="color_id_add" name="color_id" class="form-control">
									@foreach($colors as $color)
									<option value="{{ $color->id }}">{{ $color->name }}</option>
									@endforeach
								</select>
								<span id="span_color_id_add"></span>
							</div>	
							<div class="form-group">
								<label for="">* Memory</label>
								<input class="form-control" type="text" id="memory_add" name="memory" placeholder="Memory">
								<span id="span_memory_add"></span>
							</div>	
							<div class="form-group">
								<label for="">* Price</label>
								<input class="form-control" name="price" type="number" id="price_add" placeholder="Price">
								<span id="span_price_add"></span>
							</div>	
								

					</div>
					<div style="margin-left:2%; float: left;  width: 45%;">	
						<div class="form-group">
								<label for="">* Sale price</label>
								<input class="form-control" type="number" name="sale_price" id="price_sale_add"placeholder="Sale price">
								<span id="span_sale_price_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Quantity</label>
								<input class="form-control" type="number" name="quantity" id="quantity_add"placeholder="Quantity">
								<span id="span_quantity_add"></span>
							</div>
							<div class="form-group">
								<label for="">*  Branch</label>
								<select id="branch_id_add" name="branch_id" class="form-control">
									@foreach($branches as $branch)
									<option value="{{ $branch->id }}">{{ $branch->name }}</option>
									@endforeach
								</select>
								<span id="span_branch_id_add"></span>
							</div>
					</div>
				</div>
					<div class="clear"></div>
					<div class="modal-footer">
							<button type="button" class="btn btn-default" id="close-detail-product-add" data-dismiss="modal">Close</button>
							<button type="submit"  class="btn btn-primary">Add</button>
					
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- modal update detail_products --}}
<div class="modal fade" id="modal-update_detail_products">
		<div style="width: 70%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom: 0px;">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4  >Cập nhật chi tiết sản phẩm sản phẩm :</h4>
					

				</div>
				<form id="form_update_detail_product" method="POST" role="form">
					@csrf
				<div class="modal-body">
					
					<div style="margin-left:2%; float: left;  width: 45%;">	

							<input type="hidden" name="product_id" readonly class="form-control" id="product_id_update">
							<input type="hidden"  readonly class="form-control" id="detail_product_id_update">
							<div class="form-group">
								<label for="">* Color</label>
								<select id="color_id_update" name="color_id" class="form-control">
									@foreach($colors as $color)
									<option value="{{ $color->id }}">{{ $color->name }}</option>
									@endforeach
								</select>
								<span id="span_color_id_update"></span>
							</div>	
							<div class="form-group">
								<label for="">* Memory</label>
								<input class="form-control" type="text" id="memory_update" name="memory" placeholder="Memory">
								<span id="span_memory_update"></span>
							</div>	
							<div class="form-group">
								<label for="">* Price</label>
								<input class="form-control" name="price" type="number" id="price_update" placeholder="Price">
								<span id="span_price_update"></span>
							</div>	
								

					</div>
					<div style="margin-left:2%; float: left;  width: 45%;">	
						<div class="form-group">
								<label for="">* Sale price</label>
								<input class="form-control" type="number" name="sale_price" id="sale_price_update"placeholder="Sale price">
								<span id="span_sale_price_update"></span>
							</div>
							<div class="form-group">
								<label for="">* Quantity</label>
								<input class="form-control" type="number" name="quantity" id="quantity_update"placeholder="Quantity">
								<span id="span_quantity_update"></span>
							</div>
							<div class="form-group">
								<label for="">*  Branch</label>
								<select id="branch_id_update" name="branch_id" class="form-control">
									@foreach($branches as $branch)
									<option value="{{ $branch->id }}">{{ $branch->name }}</option>
									@endforeach
								</select>
								<span id="span_branch_id_update"></span>
							</div>
					</div>
				</div>
					<div class="clear"></div>
					<div class="modal-footer">
							<button type="button" id="close-detail-product-update" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit"  class="btn btn-primary">Save</button>
					
					</div>
				</form>
			</div>
		</div>
	</div>



{{-- modal REVIEW --}}
<div class="modal fade" id="modal-reviews">
		<div style="width:80%;" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom: 0px;">
					
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4  >Chi tiết sản phẩm :</h4>
					

				</div>
				<a style="margin-left: 5%;margin-top: 3%; " id="add_review" href="javascript:;" class="btn btn-success">
						Add Review
					</a>
				<div class="modal-body">
					
					<div style="margin: auto; width: 90%;">
						<table class="table" id="reviews-table">
							<thead >
								<tr>
									<th>Id</th>
									<th>Product name</th>
									<th>Image</th>
									<th>Description</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

				</div>
				<div class="clear"></div>
				<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						
				</div>
			</div>
		</div>
	</div>

	{{-- modal add reviews --}}
		<div class="modal fade"  id="modal-add_reviews">
			<div style="width: 70%;" class="modal-dialog">
				<div class="modal-content">

					<form action="" id="form-add_reviews"  role="form" enctype="multipart/form-data">
						@csrf
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Add Review</h4>
						</div>
							<div style="  width: 45%; float: left; ">
								<div style="width: 50%; margin:5% auto 5%;">
								<img style="width: 100%; height: 100%;" src="/storage/default_image.png" class="avatar img-circle img-thumbnail" alt="avatar">
								<span id="span_thumbnail_add"></span>
								</div>
									<div class="clear"></div>	
								<input style="margin:5% auto 5%;" type="file"  class="text-center center-block file-upload" name ="thumbnail" id="thumbnail_add"  placeholder="Thumbnail">
								<input type="hidden" readonly id="product_id_add_reviews" name="product_id">
								
							</div>
							
						
							
						<div style="float: left; width: 50%; margin-top: 1%;" class="modal-body">
							
							<div class="form-group">
								<label for="">* Description</label>
								<input type="text" class="form-control description" id="description_add"  name ="description" placeholder="Description">
								<span id="span_description_add"></span>
							</div>
							<div class="form-group">
								<label for="">* Content</label>
								<input type="text" class="form-control" id="content_add"  name ="content" placeholder="Content">
								<span id="span_content_add"></span>
							</div>
						</div>
						<div class="clear"></div>
						<div class="modal-footer">
							<button type="button" id="close-review-add" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit"  class="btn btn-primary">Add</button>
						</div>
					</form>
				
			</div>
		</div>
		</div>

		{{-- modal show reviews --}}
		<div class="modal fade"  id="modal-show_reviews">
			<div style="width: 70%;" class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Show Review</h4>
						</div>
							
						
							
						<div style="width: 90%; margin:auto;" class="modal-body">	
							<div style="width: 43%; float: left; margin:3% ;">
								<img id="thumbnail_show_review" style="width: 100%; height: 100%;">
							</div>
							<div style="width: 43%;  float: left; margin:3%;">
								<h4 >Tên sản phẩm: <span id="product_name_review"></span></h4>
							
								<h5 id="description_show_review"></h5>
								
								<p id="content_show_review"></p>
							</div>
							
							
						</div>
						<div class="clear"></div>
						<div class="modal-footer">
							<button type="button"  class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit"  class="btn btn-primary">Add</button>
						</div>
					
				
			</div>
		</div>
		</div>

{{-- modal edit reviews --}}
		<div class="modal fade"  id="modal-update_reviews">
			<div style="width: 70%;" class="modal-dialog">
				<div class="modal-content">

					<form action="" id="form-update_review"  role="form" enctype="multipart/form-data">
						@csrf
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Update Review</h4>
						</div>
							<div style="  width: 45%; float: left; ">
								<div style="width: 50%; margin:5% auto 5%;">
								<img style="width: 100%; height: 100%;" id="image_review_update" class="avatar img-circle img-thumbnail" alt="avatar">
								<span id="span_thumbnail_update"></span>
								</div>
									<div class="clear"></div>	
								<input style="margin:5% auto 5%;" type="file"  class="text-center center-block file-upload" name ="thumbnail" id="thumbnail_update"  placeholder="Thumbnail">
								<input type="hidden" readonly id="product_id_update_reviews" name="product_id">
								<input type="hidden" name="_method" id="put_update" value="put">
								<input type="hidden" name="id" id="review_id">
								
							</div>
							
						
							
						<div style="float: left; width: 50%; margin-top: 1%;" class="modal-body">
							
							<div class="form-group">
								<label for="">* Description</label>
								<input type="text" class="form-control description" id="description_update"  name ="description" placeholder="Description">
								<span id="span_description_update"></span>
							</div>
							<div class="form-group">
								<label for="">* Content</label>
								<input type="text" class="form-control" id="content_update"  name ="content" placeholder="Content">
								<span id="span_content_update"></span>
							</div>
						</div>
						<div class="clear"></div>
						<div class="modal-footer">
							<button type="button" id="close-review-update" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit"  class="btn btn-primary">Save</button>
						</div>
					</form>
				
			</div>
		</div>
		</div>
@endsection
@section('js')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script> --}}
  {{-- <script>tinymce.init({ selector:'.description' });</script> --}}
  

<script type="text/javascript" src="/js/mainProduct.js"></script>
{{-- <script>tinymce.init({ selector:'#description_add' });</script> --}}

@endsection