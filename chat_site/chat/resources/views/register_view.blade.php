@extends('layouts.login_register_layout')
@section('content')
    <form class="border rounded p-3" action="{{ Route('register_user') }}" method="POST">

        <div class="form-group">
            <label for="register_uname">Username</label>
            <input type="text" class="form-control" name="uname" id="register_uname" placeholder="Enter email">
        </div>
        @error('uname')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label for="register_email">Email address</label>
            <input type="text" class="form-control" name="email" id="register_email" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        @error('email')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label for="register_password">Password</label>
            <input type="password" class="form-control" name="pass" id="register_password" placeholder="Password">
        </div>
        @error('pass')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label for="comfirm_register_password">Confirm password</label>
            <input type="password" class="form-control" name="cpass" id="comfirm_register_password" placeholder="Password">
        </div>
        @error('cpass')
            <p class="text-danger">{{ $message }}</p>
        @enderror


        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="register_check_pass">
            <label class="form-check-label" for="register_check_pass">Check me out</label>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Submit</button>
        <br>
        Redirect page to <a href="{{ Route('register_view') }}">Login.</a>
        @csrf
    </form>
@endsection

@section('script')
<script>
    $('#register_check_pass').on('click',function(){
        if($(this).prop("checked") == true){
            $('#register_password').attr('type','text');
            $('#comfirm_register_password').attr('type','text');
        }else{
            $('#register_password').attr('type','password');
            $('#comfirm_register_password').attr('type','password');
        }
    });
</script>
@endsection