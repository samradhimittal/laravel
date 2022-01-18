<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>{{$title}}</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
      <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
   </head>
   <body>
      <div class="container mt-2">
         <div class="row">
            <div class="col-lg-12 margin-tb">
               <div class="pull-left">
                  <h2>User List</h2>
               </div>
               <div class="pull-right mb-2">
                  <a class="btn btn-success" onClick="add()" href="javascript:void(0)"> Add Interest</a>
               </div>
            </div>
         </div>
         @if ($message = Session::get('success'))
         <div class="alert alert-success">
            <p>{{ $message }}</p>
         </div>
         @endif
         <div class="card-body">
            <table class="table table-bordered" id="ajax-crud-datatable">
               <thead>
                  <tr>
                     <th>Id</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Profile Pic</th>
                     <th>Interest</th>
                     <th>Created At</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
      <!-- boostrap user model -->
      <div class="modal fade" id="user-modal" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="UserModel"></h4>
               </div>
               <div class="modal-body">
                  @include('auth.register')
               </div>
               <div class="modal-footer"></div>
            </div>
         </div>
      </div>
      <!-- end bootstrap model -->

      <!-- boostrap user model -->
      <div class="modal fade" id="edit-user-modal" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title" id="editUserModel">Edit User</h4>
               </div>
               <div class="modal-body" id="editUserDetail">
                 
               </div>
            </div>
         </div>
      </div>
      <!-- end bootstrap model -->
   </body>

   <script type="text/javascript">
      $(document).ready( function () {
      $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $('#ajax-crud-datatable').DataTable({
                  processing: true,
                  serverSide: true,
                  ajax: "{{ url('users') }}",
                     columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'name' },
                        { 
                           data: 'profile_pic', 
                           name: 'profile_pic' ,
                           render : function (data, type, full, meta) {        
                              return '<img src="/uploads/avatars/' + data + '" height="50px"/>';    
                           },     
                        },
                        { data: 'interests', name: 'interests' },
                        { data: 'created_at', name: 'created_at' },
                        {data: 'action', name: 'action', orderable: false},
                     ],
                     order: [[0, 'desc']]
                  });
            });
      function add(){
            $('#UserForm').trigger("reset");
            $('#UserModel').html("Add Interest");
            $('#user-modal').modal('show');
            $('#id').val('');
      }   
      function editFunc(id){
         $.ajax({
            type:"POST",
            url: "{{ url('edit-user') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                  console.log(res);
                  $('#editUserDetail').html(res.html);
                  $('#edit-user-modal').modal('show');
            }
         });
      }  
      function deleteFunc(id){
      if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
      type:"POST",
      url: "{{ url('delete-user') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
      var oTable = $('#ajax-crud-datatable').dataTable();
      oTable.fnDraw(false);
      }
      });
      }
      }
      $('#UserForm').submit(function(e) {
         e.preventDefault();
         var formData = new FormData(this);
         $.ajax({
               type:'POST',
               url: "{{ url('store-user')}}",
               data: formData,
               cache:false,
               contentType: false,
               processData: false,
               success: (data) => {
                    if($.isEmptyObject(data.error)){
                        $("#user-modal").modal('hide');
                        var oTable = $('#ajax-crud-datatable').dataTable();
                        oTable.fnDraw(false);
                        $("#btn-save").html('Submit');
                        $("#btn-save"). attr("disabled", false);
                    }else{
                        printErrorMsg(data.error);
                    }
                       
               },
               error: function(data){
                  console.log(data);
               }
         });
      });

       function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });
        }

      
   </script>
</html>