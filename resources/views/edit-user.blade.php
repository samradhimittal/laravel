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

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Last Name</label>

                            <div class="col-md-6">
                                <input id="elast_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{$user['last_name']}}"  autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="eemail" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user['email'] }}"  autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Select Hobbies</label>

                            <div class="col-md-6">

                                @foreach($interests as $key => $interest)
                                <input type="checkbox" name="interests[]" 
                                value="{{$interest->id}}"   
                                @if(isset($user["interest"][$key]["interest_id"]) && $user["interest"][$key]["interest_id"]==$interest->id)
                                {{ 'checked' }}
                                @endif>
                                {{$interest->name}}
                                @endforeach
                            </div>

                            @error('interests')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Upload profile pic</label>
                            <img src="{{ asset('/uploads/avatars/'.$user->profile_pic)}}" height="50px">
                            <input type="hidden" name="profile_pic" value="{{$user['profile_pic']}}">
                            <div class="col-md-6">
                                <input type="file" name="avatar">
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
            
    