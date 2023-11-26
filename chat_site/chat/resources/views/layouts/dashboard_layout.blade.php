<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Material Ui icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.10/iconfont/material-icons.min.css">

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- Style CSS -->
   <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    @yield('chat_dash')
    @yield('script')
    <script>
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

        function get_all_messages(user_id)
        {
            var token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route("get_all_messages") }}',
                method: 'POST',
                data: { 
                        '_token': token,
                        'user_id':user_id
                    },
                beforeSend: function() {
                    $('.chatbox').css('display','none');
                    $('.chatbox_lorder').removeClass('d-none');
                },
                success: function (res) 
                {
                    $('.chatbox_lorder').addClass('d-none');
                    $('.chatbox').css('display','flex');
                    
                    if (res.status == true) {
                        if($('.chatbox').append(res.messages)){
                            $(".chat_part").animate({ scrollTop: 20000000 }, "slow");
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

        // ===== Start : Show dropdown at the right click on message ===== //
        var copy_msg = '';
        $(document).on('contextmenu', '.msg_dropdown', function(e) {
            e.preventDefault();

            var html = '';
            var msg_text = $(this).find('.msg_text').text().toString();
            var msg_id = $(this).attr('msg_id');
            copy_msg = msg_text;

            if($('.msg_dd').remove())
            {
                html += '<div class="msg_dd" style="position: absolute; top:'+ (e.pageY) + 'px;left: '+ e.pageX +'px; width: 120px; height: 100px; background: white; border-radius: 8px; display: flex; flex-direction: column; justify-content: space-around;box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">';
                html += '    <div class="ps-3"> <i class="material-icons">edit</i> Edit </div>';
                html += '    <div class="ps-3" onclick="copy_text()"> <i class="material-icons">content_copy</i> Copy </div>';
                html += '    <div class="ps-3" onclick="delete_msg('+msg_id+')"> <i class="material-icons">delete</i> Delete </div>';
                html += '</div>';
                $('body').append(html);
            }
        });

        function copy_text() {
            navigator.clipboard.writeText(copy_msg);
            copy_msg = '';
        }

        function delete_msg(msg_id)
        {
            var confirmation = window.confirm('Are you sure you want to delete this message?');
            if(confirmation)
            {
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ route("delete_message") }}',
                    method: 'post',
                    data: {
                        '_token': token,
                        'msg_id' : msg_id
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('.chatbox').css('display','none');
                        $('.chatbox_lorder').removeClass('d-none');
                    },
                    success: function(res)
                    {
                        $('.chatbox_lorder').addClass('d-none');
                        $('.chatbox').css('display','flex');
    
                        if(res.status == true) {
                            get_all_messages(res.opposite_user_id);
                            conn.send(res.opposite_user_id);
                        }else{
                            alert("You can't delete message from the opposite user.");
                        }
    
                        $(".chat_part").animate({ scrollTop: 20000000 }, "slow"); 
                    },
                });
            }
        }
        // ===== End : Show dropdown at the right click on message ===== //
        
        $(document).on('click',function() {
            if($(document).find('.msg_dd').is(':visible') == true)
            {
                $(document).find('.msg_dd').remove();
            }
        });
    </script>
</body>
</html>
