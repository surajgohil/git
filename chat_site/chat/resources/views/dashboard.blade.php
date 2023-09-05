{{--
// Section List //  
1 User Control
    1 User List
2 Chat Area 
    1 Opposite User Details
    2 Chat Part
    3 Input Part
--}}

@extends('layouts.dashboard_layout')
@section('chat_dash')
    @php
        $login_user_color = str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    @endphp

    <div class="main_div row">

        {{-- section 1 : User Control --}}
        <div class="col-3 left_part">
            <div class="user_control">
                {{-- Login User Details --}}
                <div class="login_user_details">
                    <div class="user_details">
                        <div class="user_short_name" style="background: #{{ $login_user_color }};"> {{ substr( Session::get('name'),0,2) }} </div>
                        <div class="user_full_name"> {{ Session::get('name') }} </div>
                    </div>

                    <div class="chat_list_show_option">
                        <span> All </span>
                        <span> Group </span>
                        <span> Single </span>
                        <span> Call </span>
                    </div>
                </div>


                {{-- Filter --}}
                <div class="user_filter">
                    <div class="filter_user_css">
                        <div class="search_box">
                            <input type="text" placeholder="Search...">
                        <i class="material-icons search_icon">search</i>
                        </div>
                        <i class="material-icons">filter_list</i>
                    </div>
                </div>

                {{-- User List --}}
                <div class="user_list_container">
                    @foreach ($user_list as $udata)
                        @if ($udata['id'] != Session::get('id'))
                            <div class="user_details" user_id="{{ substr($udata['id'],0,2) }}">
                                <div class="user_area w-100" user_id="{{ $udata['id'] }}">
                                    <div class="user_short_name" style="background: #{{ str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT) }};"> 
                                        {{ substr($udata['user_name'],0,2) }} 
                                    </div>
                                    <div class="user_full_name"> {{ $udata['user_name'] }} </div>
                                </div>
                                <div class="invite_user" user_id="{{ $udata['id'] }}">
                                    <i class="material-icons">control_point</i>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>


        {{-- section 2 : Chat Area --}}
        <div class="col-9 right_part">

            {{-- 1 Opposite User Details --}}
            <div class="col-12 opposite_user">
                <div class="user_details">
                    @if ($udata['id'] == Session::get('id'))
                        <div class="user_short_name" style="background: #{{ $login_user_color }};"> me </div>
                        <div class="user_full_name"> me </div>    
                    @else
                        <div class="user_short_name" style="background: #{{ $login_user_color }};"> {{ substr(Session::get('name'),0,2) }} </div>
                        <div class="user_full_name"> {{ Session::get('name') }} </div>
                    @endif 
                </div>
                <div class="connect_with_network">
                    <div class="user_videocam material-icons"> videocam </div>
                    <div class="user_call material-icons"> call </div>
                </div>
            </div>

            {{-- 2 Chat Part --}}
            <div class="col-12 chat_part">
                <div class="chatbox">
                    {{-- <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">Hi</span></div>
                    <div><span>Hi</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">Good Morning</span></div>
                    <div><span>Good Morning</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">How Are You?</span></div>
                    <div><span>I Am Fine</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">Hi</span></div>
                    <div><span>Hi</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">Good Morning</span></div>
                    <div><span>Good Morning</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">How Are You?</span></div>
                    <div><span>I Am Fine</span></div>
                    <div style="justify-content: end;"><span style="border-radius: 10px 0px 0px 10px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span></div> --}}
                </div>
            </div>


            {{-- 3 Input Part --}}
            <div class="col-12 input_part container-fluid"> 
                <input type="hidden" id="select_user_id">
                <div class="row h-100">
                    <div class="col-11 message_input">
                        <textarea type="text" name="message" id="send-btn" placeholder="Message..."></textarea>
                    </div>
                    <div class="col-1 send_message_btn">
                        <button class="btn">
                            <i class="material-icons">send</i>
                        </button>
                    </div>
                </div>
            </div>


        </div>

    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        var conn = new WebSocket('ws://127.0.0.1:8080');
        
        conn.onopen = function(e) 
        {
            console.log("Connection established!");
            var user_id = {{ Session::get("id") }};
            get_all_messages(user_id);
        };

        conn.onmessage = function(e) 
        {
            var user_id = $('#select_user_id').attr('user_id');
            get_all_messages(user_id);
        };
        
        $(document).on('keypress',function(e) 
        {
            if(e.which == 13) {
                send_msg();
            }
        });

        $('.user_list_container .user_details .user_area').click(function()
        {
            var receiver_id = $(this).attr('user_id');
            var slt_user_short_name = $(this).find('.user_short_name').text();
            var slt_user_short_name_bg = $(this).find('.user_short_name').attr('style');
            var slt_user_long_name = $(this).find('.user_full_name').text();

            $('#select_user_id').attr('user_id',receiver_id);
            $('.opposite_user .user_short_name').text(slt_user_short_name);
            $('.opposite_user .user_short_name').attr('style',slt_user_short_name_bg);
            $('.opposite_user .user_full_name').text(slt_user_long_name);
            $('.chatbox').empty();
            get_all_messages(receiver_id);
        });

        function send_msg()
        {
            var msg = $('#send-btn').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            var receiver_id = $('#select_user_id').attr('user_id');
            
            $('#send-btn').val('');
            
            $.ajax({
                url: '{{ route("send_message") }}',
                method: 'POST',
                data: { 
                        '_token': token,
                        'msg': msg, 
                        'receiver_id':receiver_id
                    },
                dataType: 'json',
                success: function (res) 
                {
                    if (res.status == true) 
                    {
                        conn.send(msg);
                        var html = '<div style="justify-content: end;"><span style="border-radius: 8px 8px 0px 8px">'+res.msg+'</span></div>';
                        $('.message_not_found_error').remove();
                        $('.chatbox').append(html);
                    } else {
                        console.error('AJAX request failed.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            }); 
        }

        function scroll_down()
        {
            var scroll_name = $(".chatbox");
            $(scroll_name).animate({ scrollTop: scroll_name.height() }, 1000);
        }

        function get_all_messages(user_id)
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route("get_all_message") }}',
                method: 'POST',
                data: { 
                        '_token': token,
                        'user_id':user_id
                    },
                success: function (res) {
                    if (res.status == true) {
                        if($('.chatbox').append(res.messages)){
                            // scroll_down();
                            $('.chatbox').animate({ scrollTop: $(document).height() }, 1000);
                        }
                    } else {
                        console.error('AJAX request failed.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });
        }
        
        $('.invite_user').on('click',function()
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            var user_id = $(this).attr('user_id');
            
            $.ajax({
                url: '{{ route("invite_user") }}',
                method: 'POST',
                data: { 
                        '_token': token,
                        'user_id':user_id
                    },
                success: function (res) 
                {
                    alert('done...');
                    // if (res.status == true) {
                    // } else {
                    // }
                },
                error: function (xhr, status, error) {
                    console.error('invite_user AJAX error:', error);
                }
            });
        });
        
    });
</script>
@endsection