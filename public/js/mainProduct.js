$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
$(function(){
   
	$('#products-table').DataTable({
		processing: true,
        serverSide: true,
        ajax:'/admin/getProducts',
        columns:[

            { data: 'name', name: 'name' },
            { data: 'product_details', name: 'product_details' },
            { data: 'brand_id', name: 'brand_id' },
            { data: 'category_id', name: 'category_id' },
            { data: 'action', name: 'action' }
        ]
	});



       
     /**
         * Tác dụng :dropzone
         *
         * @param  name space
         * @param  int   biến chuyền vào
         * @return \Illuminate\Http\Response trả về gì
         */
         Dropzone.options.myDropzone = {
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 10,
            maxFilesize: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            dictFileTooBig: 'Image is bigger than 5MB',
            addRemoveLinks: true,
          init: function() {
              var submitButton = document.querySelector("#save_images")
              myDropzone = this;
              submitButton.addEventListener("click", function() {
                  myDropzone.processQueue(); 
                  $('#modal-images').modal('hide');
                  toastr.success('Add images success !')
              });
        
        },
    };
       $(document).on('click','.btn-images',function(){
           $('#modal-images').modal('show');
           var id = $(this).data('id');
            $('#product_id_add_images').val(id);
            $.ajax({
              type:'get',
              url:'/admin/products/'+id,
              success :function(reponse){
                $('#list_images').children().remove();
                if(reponse.images!=null){
                      jQuery.each(reponse.images,function(key,value){
                  $('#list_images').append(`
                    <div id="image-`+value.id+`" style ="width:23%;float:left; margin-left:1%;text-align:center;">
                      <img style="width:100%;height:100%;" src="/storage/`+value.thumbnail+`" alt="" />
                     
                      <a style="margin:3%;" href="javascript:;"   class="btn btn-delete_image btn-danger" data-id="`+value.id+`">Delete Image</a>
                    
                    </div>`)
                  })  
                }
            
              }
            })
        });
    $(document).on('click','.btn-delete_image',function(){
      var id = $(this).data('id');
      swal({
            title: "Bạn có muốn xóa không ?",
            text: "Sau khi xóa sẽ không thể khôi phục lại!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            $.ajax({ 

                type:'delete',
                url:'/admin/images/'+id,
                success : function(reponse){
            
                    
                    toastr.success('Delete success!');
                    $('#image-'+id).remove();
            }

       })
                swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                });
            } else {
                swal("Bạn đã hủy chức năng xóa!");
            }
        })

    })
    $(document).on('click','.btn-show',function(){
        $('#modal-show').modal('show'); 
        
        var id = $(this).data('id');
        $.ajax({
          type:'get',
          url:'/admin/products/'+id,
          success : function(reponse){
            $('#product_name').html(reponse.product.name)
            $('#product_slug').html(reponse.product.slug)
            $('#product_code').html(reponse.product.code)
            $('#product_user_id').html(reponse.user.name)
            $('#product_category_id').html(reponse.category.name)
            $('#product_brand_id').html(reponse.brand.name)
            $('#product_warranty_time').html(reponse.product.warranty_time)
            $('#product_ram').html(reponse.product.ram)
            $('#product_weight').html(reponse.product.weight)
            $('#product_screen_size').html(reponse.product.screen_size)
            $('#product_pin').html(reponse.product.pin)
            $('#product_front_camera').html(reponse.product.front_camera)
            $('#product_rear_camera').html(reponse.product.rear_camera)
            
            $('#product_operating_system').html(reponse.product.operating_system);
            $('#images-div').children().remove();
            jQuery.each(reponse.images,function(key,value){
              $('#images-div').append(`
                <div style ="width:25%;float:left; margin:auto;">
                <img style="width:100%;height:100%;" src="/storage/`+value.thumbnail+`" alt="" />
                </div>`)
            })
          }
        })
    })

    /////////////////
    $('.btn-add').on('click',function(){
        $('#modal-add').modal('show');
        $('#name_add').keyup(function() {
        var takedata = $('#name_add').val()
        $('#slug_add').val(to_slug(takedata));

        });
         $('#span_name_add').html('');
        $('#span_slug_add').html('');
        $('#span_code_add').html('');
        $('#span_user_id_add').html('');
        $('#span_category_id_add').html('');
        $('#span_brand_id_add').html('');
        $('#span_warranty_time_add').html('');
        $('#span_ram_add').html('');
        $('#span_weight_add').html('');
        $('#span_screen_size_add').html('');
        $('#span_pin_add').html('');
        $('#span_front_camera_add').html('');
        $('#span_rear_camera_add').html('');
        $('#span_operating_system_add').html('');
    })
    $('#form-add').submit(function(e){
        e.preventDefault();
        var data = $('#form-add').serialize();
        $('#span_name_add').html('');
        $('#span_slug_add').html('');
        $('#span_code_add').html('');
        $('#span_user_id_add').html('');
        $('#span_category_id_add').html('');
        $('#span_brand_id_add').html('');
        $('#span_warranty_time_add').html('');
        $('#span_ram_add').html('');
        $('#span_weight_add').html('');
        $('#span_screen_size_add').html('');
        $('#span_pin_add').html('');
        $('#span_front_camera_add').html('');
        $('#span_rear_camera_add').html('');
        $('#span_operating_system_add').html('');
        $.ajax({
            type:'post',
            url:'/admin/products',
            data:data,
            success: function(reponse){
               toastr.success('Add success!');
                $('#modal-add').modal('hide');
                $('#products-table').DataTable().ajax.reload(); 
            },
            error: function(jq, status , throwE){
                console.log(jq)
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_add').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })  
            }
        })
    });
     
    $(document).on('click','.btn-edit',function(){

        $('#modal-update').modal('show');
        var id = $(this).data('id');
        $('#name_update').keyup(function() {
        var takedata = $('#name_update').val()
        $('#slug_update').val(to_slug(takedata));

        });
                $('#span_name_update').html('');
                $('#span_slug_update').html('');
                $('#span_code_update').html('');
                $('#span_user_id_update').html('');
                $('#span_category_id_update').html('');
                $('#span_brand_id_update').html('');
                $('#span_warranty_time_update').html('');
                $('#span_ram_update').html('');
                $('#span_weight_update').html('');
                $('#span_screen_size_update').html('');
                $('#span_pin_update').html('');
                $('#span_front_camera_update').html('');
                $('#span_rear_camera_update').html('');
                $('#span_operating_system_update').html('');
        
        $.ajax({
            type:'get',
            url:'/admin/products/'+id+'/edit',
            success:function(reponse){
                $('#id_update').val(reponse.id);
                $('#name_update').val(reponse.name);
                $('#slug_update').val(reponse.slug);
                $('#code_update').val(reponse.code);
                $('#user_id_update > option[value="'+reponse.user_id+'"]').attr("selected", "selected");
                $('#category_id_update > option[value="'+reponse.category_id+'"]').attr("selected", "selected");
                $('#brand_id_update > option[value="'+reponse.brand_id+'"]').attr("selected", "selected");
                $('#warranty_time_update').val(reponse.warranty_time);
                $('#ram_update').val(reponse.ram);
                $('#weight_update').val(reponse.weight);
                $('#screen_size_update').val(reponse.screen_size);
                $('#pin_update').val(reponse.pin);
                $('#front_camera_update').val(reponse.front_camera);
                $('#rear_camera_update').val(reponse.rear_camera);
                $('#operating_system_update').val(reponse.operating_system);
                
            }
        })
    })
    $('#form-update').submit(function(e){
        e.preventDefault();
        var id = $('#id_update').val();
        var data = $('#form-update').serialize();
                $('#span_name_update').html('');
                $('#span_slug_update').html('');
                $('#span_code_update').html('');
                $('#span_user_id_update').html('');
                $('#span_category_id_update').html('');
                $('#span_brand_id_update').html('');
                $('#span_warranty_time_update').html('');
                $('#span_ram_update').html('');
                $('#span_weight_update').html('');
                $('#span_screen_size_update').html('');
                $('#span_pin_update').html('');
                $('#span_front_camera_update').html('');
                $('#span_rear_camera_update').html('');
                $('#span_operating_system_update').html('');

        $.ajax({
            type:'put',
            url:'/admin/products/'+id,
            data:data,
            success:function(reponse){
                    toastr.success('Update success !');
                    $('#modal-update').modal('hide');
                   $('#products-table').DataTable().ajax.reload(); 
            },
            error:function(jq,status,throwE){
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_update').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })
            }

        })
    })
    ///delete
    $(document).on('click','.btn-delete',function(){
        var id = $(this).data('id');
        var trID = $('#'+id);
        swal({
            title: "Bạn có muốn xóa không ?",
            text: "Sau khi xóa sẽ không thể khôi phục lại!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            $.ajax({ 

                type:'delete',
                url:'/admin/products/'+id,
                success : function(reponse){
            
                    $('#products-table').DataTable().ajax.reload();
                    toastr.success('Delete success!');
            }

       })
                swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                });
            } else {
                swal("Bạn đã hủy chức năng xóa!");
            }
        })
    })


    //////////////////
    function to_slug(str)
{
    // Chuyển hết sang chữ thường
    str = str.toLowerCase();     
 
    // xóa dấu
    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    str = str.replace(/(đ)/g, 'd');
 
    // Xóa ký tự đặc biệt
    str = str.replace(/([^0-9a-z-\s])/g, '');
 
    // Xóa khoảng trắng thay bằng ký tự -
    str = str.replace(/(\s+)/g, '-');
 
    // xóa phần dự - ở đầu
    str = str.replace(/^-+/g, '');
 
    // xóa phần dư - ở cuối
    str = str.replace(/-+$/g, '');
 
    // return
    return str;
}


/*####################################################################################*/
$(document).on('click','.btn-list_detail_products',function(){
    $('#modal-detail_products').modal('show');
    var id = $(this).data('id');
    $('#product_id_add').val(id);
    $('#detail_products-table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax:'/admin/getDetailProducts/'+id,
        columns:[
            { data: 'product_name', name: 'product_name' },
            { data: 'memory', name: 'memory' },
            { data: 'color_name', name: 'color_name' },
            { data: 'sale_price', name: 'sale_price' },
            { data: 'quantity', name: 'quantity' },
            { data: 'action', name: 'action' }
        ]
    });

})
$('#close-detail-product-add').on('click',function(){
     $('#modal-add_detail_products').modal('hide');
     $('#modal-detail_products').modal('show');
      
})
    $(document).on('click','#add_product_detal',function(){
       $('#modal-detail_products').modal('hide');
       $('#modal-add_detail_products').modal('show');

       $('#span_color_id_add').html('')
       $('#span_memory_add').html('')
       $('#span_price_add').html('')
       $('#span_sale_price_add').html('')
       $('#span_quantity_add').html('')
       $('#span_branch_id_add').html('')
    })
    $('#form_add_detail_product').submit(function(e){
        e.preventDefault();
        data = $('#form_add_detail_product').serialize();
           $('#modal-detail_products').modal('hide');
           $('#modal-add_detail_products').modal('show');

           $('#span_color_id_add').html('')
           $('#span_memory_add').html('')
           $('#span_price_add').html('')
           $('#span_sale_price_add').html('')
           $('#span_quantity_add').html('')
           $('#span_branch_id_add').html('')
        $.ajax({
            type:'post',
            url:'/admin/detail_products',
            data:data,
            success:function(reponse){
                $('#modal-add_detail_products').modal('hide');
                toastr.success('Add detail products success !');
                $('#detail_products-table').DataTable().ajax.reload();
                $('#modal-detail_products').modal('show');
            },
            error:function(jq,status,throwE){
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_add').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })
            }
        })
    })

    /**
         * Tác dụng : Update detail product
         *
         *@param  name space
         * @param  int   biến chuyền vào
         * @return \Illuminate\Http\Response trả về gì
         */

         $('#close-detail-product-update').on('click',function(){
     $('#modal-update_detail_products').modal('hide')
     $('#modal-detail_products').modal('show');
      
})
         $(document).on('click','.btn-edit-detail-product',function(){
            $('#modal-detail_products').modal('hide');
            $('#modal-update_detail_products').modal('show')
            $('#span_color_id_update').html('');
            $('#span_memory_update').html('');
            $('#span_price_update').html('');
            $('#span_sale_price_update').html('');
            $('#span_quantity_update').html('');
            $('#span_branch_id_update').html('');
            
            
            id = $(this).data('id');
            $.ajax({
                type:'get',
                url:'/admin/detail_products/'+id+'/edit',
                success :function(reponse){
                    $('#detail_product_id_update').val(reponse.id);
                 $('#product_id_update').val(reponse.product_id);
                 $('#color_id_update').val(reponse.color_id);
                 $('#memory_update').val(reponse.memory);
                 $('#price_update').val(reponse.price);
                 $('#sale_price_update').val(reponse.sale_price);
                 $('#quantity_update').val(reponse.quantity);
                 $('#branch_id_update').val(reponse.branch_id);
                }
            })
         });
         /**
              * Tác dụng :Update detail product
              *
              * @param  name space
              * @param  int   biến chuyền vào
              * @return \Illuminate\Http\Response trả về gì
              */
        $('#form_update_detail_product').submit(function(e){
            e.preventDefault();

            var id = $('#detail_product_id_update').val();
            data=$('#form_update_detail_product').serialize();

            $('#modal-detail_products').modal('hide');
            $('#modal-update_detail_products').modal('show')
            $('#span_color_id_update').html('');
            $('#span_memory_update').html('');
            $('#span_price_update').html('');
            $('#span_sale_price_update').html('');
            $('#span_quantity_update').html('');
            $('#span_branch_id_update').html('');
            $.ajax({
                type:'put',
                url:'/admin/detail_products/'+id,
                data:data,
                success:function(reponse){
                     $('#modal-update_detail_products').modal('hide');
                    toastr.success('Update detail products success !');
                    $('#detail_products-table').DataTable().ajax.reload();
                    $('#modal-detail_products').modal('show');
                },
                error:function(jq,status,throwE){
                    jQuery.each(jq.responseJSON.errors,function(key,value){
                        $('#span_'+key+'_update').html('<p style ="color:red;">'+value+'</p>');
                        toastr.error(value);
                    })
                }
            })
        });



        // delete detail product 
        $(document).on('click','.btn-delete-detail-product',function(){
        var id = $(this).data('id');
        
        swal({
            title: "Bạn có muốn xóa không ?",
            text: "Sau khi xóa sẽ không thể khôi phục lại!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            $.ajax({ 

                type:'delete',
                url:'/admin/detail_products/'+id,
                success : function(reponse){
            
                    $('#detail_products-table').DataTable().ajax.reload();
                    toastr.success('Delete success!');
            }

       })
                swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                });
            } else {
                swal("Bạn đã hủy chức năng xóa!");
            }
        })
    })

     /*####################################################################################*/
$(document).on('click','.btn-reviews',function(){
    $('#modal-reviews').modal('show');
    var id = $(this).data('id');
    $('#product_id_add_reviews').val(id);
    $('#reviews-table').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax:'/admin/getReviews/'+id,
        columns:[
            { data: 'id', name: 'id' },
            { data: 'product_name', name: 'product_name' },
            { data: 'thumbnail', name: 'thumbnail' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action' }
        ]
    });

    })   
 $('#close-review-add').on('click',function(){
     
        $('#modal-add_reviews').modal('hide');
        $('#modal-reviews').modal('show');
      
})
    $(document).on('click','#add_review',function(){
        $('#modal-reviews').modal('hide');
        $('#modal-add_reviews').modal('show');


        $('#span_thumbnail_add').html('');
        $('#span_description_add').html('');
        $('#span_content_add').html('');
    })
    $('#form-add_reviews').submit(function(e){
        e.preventDefault();
         var data = new FormData();
        data.append('_token',$('meta[name="csrf-token"]').attr('content'));
        data.append('thumbnail',$('#thumbnail_add')[0].files[0] );
        data.append('description',$('#description_add').val());
        data.append('content',$('#content_add').val());
        data.append('product_id',$('#product_id_add_reviews').val());

        $('#span_thumbnail_add').html('');
        $('#span_description_add').html('');
        $('#span_content_add').html('');
        $.ajax({
            type:'post',
            url:'/admin/reviews',
            data:data,
            contentType: false,
            processData:false,
            success:function(reponse){
                $('#modal-add_reviews').modal('hide');
                $('#reviews-table').DataTable().ajax.reload();
                 toastr.success("Add review success !");
                  $('#modal-reviews').modal('show');
            
            },
            error: function(jq, status , throwE){
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_add').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })  
            }
        })
    });


    $(document).on('click','.btn-show-reviews',function(){
        var id = $(this).data('id');
         $('#modal-reviews').modal('hide');
         $('#modal-show_reviews').modal('show');
        $.ajax({
            type:'get',
            url:'/admin/reviews/'+id,
            success: function(reponse){
                console.log(reponse.review);
                $('#product_name_review').html(reponse.review.name);
                $('#thumbnail_show_review').attr("src","/storage/"+reponse.review.thumbnail);
                $('#description_show_review').html(reponse.review.description);
                $('#content_show_review').html(reponse.review.content);
            }
        })
    })


    $('#close-review-update').on('click',function(){
     
       $('#modal-update_reviews').modal('hide');
        $('#modal-reviews').modal('show');
      
})
    $(document).on('click','.btn-edit-review',function(){
         $('#modal-reviews').modal('hide');
        $('#modal-update_reviews').modal('show');
       
        $('#span_thumbnail_update').html('')
        $('#span_description_update').html('')
        $('#span_content_update').html('')
        var id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'/admin/reviews/'+id+'/edit',
            success:function(reponse){
                $('#product_id_update_reviews').val(reponse.product_id);
                if(reponse.thumbnail!=null){
                    $('#image_review_update').attr("src","/storage/"+reponse.thumbnail);
                }
                else {
                    $('#image_review_update').attr("src","/storage/default_image.png");
                }
                
                $('#description_update').val(reponse.description);
                $('#content_update').val(reponse.content);
                
                $('#review_id').val(reponse.id);

            }
        })
    })
    $('#form-update_review').submit(function(e){
        e.preventDefault();

        var id = $('#review_id').val();
        $('#span_thumbnail_update').html('')
        $('#span_description_update').html('')
        $('#span_content_update').html('')
        var data = new FormData();
        data.append('_token',$('meta[name="csrf-token"]').attr('content'));
        data.append('thumbnail',$('#thumbnail_update')[0].files[0] );
        data.append('description',$('#description_update').val());
        data.append('content',$('#content_update').val());
        data.append('_method',$('#put_update').val());
        data.append('product_id',$('#product_id_update_reviews').val());
        $.ajax({
            type:'post',
            url:'/admin/reviews/'+id,
            data:data,
            contentType: false,
            processData:false,
            success: function(reponse){
                 $('#reviews-table').DataTable().ajax.reload();
                $('#modal-update_reviews').modal('hide');
                toastr.success('Update success !');
                $('#modal-reviews').modal('show');
            },
            error: function(jq, status , throwE){
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_update').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })  
            }
        })
    })



$(document).on('click','.btn-delete-review',function(){
        var id = $(this).data('id');
        
        swal({
            title: "Bạn có muốn xóa không ?",
            text: "Sau khi xóa sẽ không thể khôi phục lại!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
            $.ajax({ 

                type:'delete',
                url:'/admin/reviews/'+id,
                success : function(reponse){
            
                    $('#reviews-table').DataTable().ajax.reload();
                    toastr.success('Delete success!');
                    $('#modal-reviews').modal('show');
            }

       })
                swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                });
            } else {
                swal("Bạn đã hủy chức năng xóa!");
            }
        })
    })




    function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.avatar').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            };
            $(document).on('change','.file-upload', function(){
                readURL(this);
            });
});