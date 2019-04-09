$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
$(function(){
   
	$('#branches-table').DataTable({
		processing: true,
        serverSide: true,
        ajax:'/admin/getBranches',
        columns:[
        	{ data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'address', name: 'address' },
            { data: 'mobile', name: 'mobile' },
            { data: 'action', name: 'action' },
            ]
	});
    $('.btn-add').on('click',function(){
        $('#modal-add').modal('show');


        $('#span_name_add').html('');
        $('#span_address_add').html('');
        $('#span_mobile_add').html('');
    })
    $('#form-add').submit(function(e){
        e.preventDefault();
        var data = $('#form-add').serialize();
        $('#span_name_add').html('');
        $('#span_address_add').html('');
        $('#span_mobile_add').html('');
        $.ajax({
            type:'post',
            url:'/admin/branches',
            data: data,
            success : function(reponse){
                // window.location.reload();
                toastr.success('Add success!');
                $('#modal-add').modal('hide');
                $('#branches-table').DataTable().ajax.reload();
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

        
         $('#span_name_update').html('');
        $('#span_mobile_update').html('');
        $('#span_address_update').html('');
        var id = $(this).data('id');
        $.ajax({
            type:'get',
            url:'/admin/branches/'+id+'/edit',
            success: function(reponse){
                $('#id_update').val(reponse.id);
                $('#name_update').val(reponse.name);
                $('#mobile_update').val(reponse.mobile);
                $('#address_update').val(reponse.address);
            }
        })
    });
    $('#form-update').submit(function(e){
        e.preventDefault();
        var id = $('#id_update').val();
        var data = $('#form-update').serialize();
        $('#span_name_update').html('');
        $('#span_mobile_update').html('');
        $('#span_address_update').html('');
        $.ajax({
            type:'put',
            url:'/admin/branches/'+id,
            data:data,
            success:function(reponse){
                    toastr.success('Update success !');
                    $('#modal-update').modal('hide');
                   $('#branches-table').DataTable().ajax.reload(); 
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
                url:'/admin/branches/'+id,
                success : function(reponse){
            
                    $('#branches-table').DataTable().ajax.reload();
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
});