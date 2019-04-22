$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
$(function(){
   var quantity=0;
	$('#list_products-table').DataTable({
		processing: true,
        serverSide: true,
        ajax:'/admin/getProductSale',
        columns:[

            { data: 'name', name: 'name' },
            { data: 'brand_id', name: 'brand_id' },
            { data: 'category_id', name: 'category_id' },
            { data: 'choose', name: 'choose' }
        ]
	});

    $(document).on('click','.btn-cart',function(){
        $('#modal-cart').modal('show');
        $('#error_messages').children().remove();
        $('#cart-table').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:'/admin/getCart',
            columns:[
                { data: 'id', name: 'id' },
                { data: 'product_name', name: 'product_name' },
                { data: 'thumbnail', name: 'thumbnail' },
                { data: 'memory_name', name: 'memory_name' },
                { data: 'color_name', name: 'color_name' },
                { data: 'quantity_buy', name: 'quantity_buy' },
                { data: 'sale_price', name: 'sale_price' },
                // { data: 'total', name: 'total' },
                { data: 'action', name: 'action' },
            ]
        });

    })

    /*#############################################################################################*/
    
    $(document).on('click','.btn-list_detail_products',function(){
        $('#modal-detail_products').modal('show');
        var id = $(this).data('id');
        $('#product_id_add').val(id);
        $('#detail_products-table').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:'/admin/getDetailProductSale/'+id,
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

    $(document).on('click', '.btn-add_to_cart',function(){
        $('#modal-add_to_cart').modal('show');
        $('#span_quantity_buy_add').html('');
        $('#modal-detail_products').modal('hide');
        var id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'/admin/sales/'+id,
            success: function(reponse){
                quantity= reponse.quantity;
                $('#detail_product_id').val(reponse.id);
                $('#quantity_remaining').html(reponse.quantity)
            }
        })
    })
    $('#quantity_buy').keyup(function(){
        if($(this).val()>quantity){
             toastr.error('Số lượng mua bạn vừa lớn hơn số lượng còn trong kho !');
              $(this).val(quantity);
        }
    })
    $('#form-add_to_cart').submit(function(e){
        e.preventDefault();
        var data = $(this).serialize()
        $('#span_quantity_buy_add').html('');
        
        $.ajax({
            type:'post',
            url:'/admin/sales',
            data:data,
            success:function(reponse){
                if(reponse.error==true){
                    toastr.error(reponse.messages);
                }
                else{
                 toastr.success(reponse.messages); 
                 $('#modal-add_to_cart').modal('hide');
                 $('#modal-detail_products').modal('show');
                 $('#quantity_buy').val(''); 

                }
            },
            error: function(jq,status,throwE){
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_add').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })
            }
        })
    })


    // update cart
    $(document).on('click','.btn-update_cart',function(){
        var rowId= $(this).data('id');

         $('#modal-update_cart').modal('show');
        $('#modal-cart').modal('hide');

         
         $('#span_quantity_buy_update').html('');
         $.ajax({
            type:'get',
            url:'/admin/sales/'+rowId+'/edit',
            success: function(reponse){
                $('#quantity_buy_update').val(reponse.cart.qty);
                quantity=reponse.detail_product.quantity;
                $('#quantity_remaining_update').html(quantity);
                $('#rowId').val(reponse.cart.rowId);
                $('#detail_product_id_update').val(reponse.detail_product.id);

                $('#quantity_buy_update').keyup(function(){
                    if($(this).val()>quantity){
                         toastr.error('Số lượng mua bạn vừa lớn hơn số lượng còn trong kho !');
                          $(this).val(quantity);
                    }
                })

            }
         })
    })

    // submit update cart
    $('#form-update_cart').submit(function(e){
        e.preventDefault();
        var data = $(this).serialize()
        $('#span_quantity_buy_update').html('');
        var rowId= $('#rowId').val();
        $.ajax({
            type:'put',
            url:'/admin/sales/'+rowId,
            data:data,
            success:function(reponse){
                if(reponse.error==true){
                    toastr.error(reponse.messages);
                }
                else{
                 toastr.success(reponse.messages); 
                 $('#modal-update_cart').modal('hide');
                 $('#cart-table').DataTable().ajax.reload();
                 $('#error_messages').children().remove();
                 $('#modal-cart').modal('show');
                }
            },
            error: function(jq,status,throwE){
                jQuery.each(jq.responseJSON.errors,function(key,value){
                    $('#span_'+key+'_add').html('<p style ="color:red;">'+value+'</p>');
                    toastr.error(value);
                })
            }
        })
    })


    $(document).on('click','.btn-delete',function(){
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
                    url:'/admin/sales/'+id,
                    success : function(reponse){
                        
                     toastr.success('Delete success!');
                    $('#cart-table').DataTable().ajax.reload();

                            
           
        }

       })
                swal("Poof! Your imaginary file has been deleted!", {
                                    icon: "success",
                                });
            } else {
                swal("Bạn đã hủy chức năng xóa!");
            }
        })
    });

// delete all cart ////////////////////////////////////////





    $(document).on('click','#btn-delete_cart',function(){
        var rowId="dsdsdsdsdsdsds";
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
                    url:'/admin/delete',
                    success : function(reponse){
                        
                     toastr.success('Delete cart success!');
                    $('#cart-table').DataTable().ajax.reload();
                    $('#modal-cart').modal('hide');
                            
           
        }

       })
                swal("Poof! Your imaginary file has been deleted!", {
                                    icon: "success",
                                });
            } else {
                swal("Bạn đã hủy chức năng xóa!");
            }
        })
    });



    // add order
    $(document).on('click','#btn-add_order',function(){
        $('#span_customer_name').html('');
        $('#span_customer_address').html('');
        $('#span_customer_mobile').html('');
        $('#span_customer_id').html('');
        $('#span_customer_email').html('');

        $('#modal-add_order').modal('show');
        
        $('#modal-cart').modal('hide');
       
    })

    $('#form-add_order').submit(function(e){
        e.preventDefault();
         $('#span_customer_name').html('');
        $('#span_customer_address').html('');
        $('#span_customer_mobile').html('');
        $('#span_customer_id').html('');
        $('#span_customer_email').html('');
        $('#error_messages').children().remove();
        var data = $('#form-add_order').serialize();
        $.ajax({
            type:'post',
            url:'/admin/orders',
            data:data,
            success: function(reponse){
                if(reponse.error==true){
                    jQuery.each(reponse.messages,function(key, value){
                          $('#span_'+key+'').html('<p style="color:red;">'+value+'</p>');
                        toastr.error(value);
                      
                    })
                  
                }
                else{
                    if(reponse.error_cart==true){
                        toastr.error(reponse.message);
                    }
                    else{
                        if(reponse.error_quantity==true){
                        console.log(reponse.messages)

                        jQuery.each(reponse.messages,function(key,value){
            toastr.error('id :'+value.cart_id+'<br>Tên :'+value.cart_name+'<br>Số lượng hiện tại còn :'+value.quantity+'. <br>Vui lòng cập nhật giỏi hàng !');       
                        $('#error_messages').append(`<p style="color:red;">Id: `+value.cart_id+` Tên sản phẩm: `+value.cart_name+` Số lượng chỉ còn:`+value.quantity+`</p>`)
                        })

                        // show and hide modal
                         $('#modal-add_order').modal('hide');
                         $('#modal-cart').modal('show');
                    }
                    else{
                         toastr.success('Order success !');
                         $('#customer_name').val('')
                         $('#customer_address').val('')
                         $('#customer_mobile').val('')
                         $('#customer_id').val('')
                         $('#customer_email').val('')
                         $('#modal-add_order').modal('hide');
                         $('#total_carts').html('');
                    }
                }
                   
                }
            },
            error: function(jq,status, throwE){
                jQuery.each(jq.responseJSON.errors,function(key, value){

                    $('#span_'+key+'').html('<p style="color:red;">'+value+'</p>');
                    toastr.error(value);
                })
            }

        })
    })

});