@extends('layouts.login_register_layout')
@section('content')
    <form class="border rounded p-3" action="{{ Route('login_user') }}" method="POST">
        <div class="form-group">
            <label for="login_email">Username</label>
            <input type="text" class="form-control" name="uname" id="login_email" placeholder="Enter email" required>
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        @error('uname')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label for="login_password">Password</label>
            <input type="password" class="form-control" name="pass" id="login_password" placeholder="Password" required>
        </div>
        @error('pass')
            <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="login_check_pass">
            <label class="form-check-label" for="login_check_pass">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit</button>
        <br>
        Redirect page to <a href="{{ Route('login_view') }}">Register.</a>
        @csrf
    </form>
@endsection

@section('script')
<script>
    $('#login_check_pass').on('click',function(){
        if($(this).prop("checked") == true){
            $('#login_password').attr('type','text');
        }else{
            $('#login_password').attr('type','password');
        }
    });
</script>
@endsection