    <div class="card">
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                <form action="javascript:void(0)" id="updateForm" name="InterestForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    
                        @csrf

                        <input type="hidden" value="{{$user['id']}}" name="id">
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">First Name</label>

                            <div class="col-md-6">
                                <input id="efirst_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{$user['first_name']}}"  autocomplete="first_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Last Name</label>

                            <div class="col-md-6">
                                <input id="elast_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{$user['last_name']}}"  autocomplete="last_name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="eemail" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user['email'] }}"  autocomplete="email">

                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Select Type</label>

                            <div class="col-md-6">
                                <select name="type">                                  
                                @foreach(Config('constants.types') as $key => $type)
                                <option value="{{$type}}" @if($user->userDetail->type==$type) {{ 'selected'}} @endif>{{$type}}</option>
                                @endforeach
                                </select>
                            </div>
                          
                        </div>

                        <div class="row mb-3">
                            <label for="amount" class="col-md-4 col-form-label text-md-end">
                            Amount </label>

                            <div class="col-md-6">
                                <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ $user->userDetail->amount }}"  autocomplete="amount">

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="date" class="col-md-4 col-form-label text-md-end">Date</label>

                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ $user->userDetail->date }}"  autocomplete="date">
                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                </form>
   </div>
   <script type="text/javascript">
       $('#updateForm').submit(function(e) {
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
                        $("#edit-user-modal").modal('hide');
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
            
    