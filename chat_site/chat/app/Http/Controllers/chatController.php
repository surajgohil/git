<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\chat;
use App\Models\invite_data;

class chatController extends Controller
{
    public function send_message(Request $request)
    {
        $data = array();
        $msg = new chat();

        if(session()->get('id') == $request->receiver_id)
        {
            // login user self conversation stored
            $msg->sender = session()->get('id');
            $msg->receiver = $request->receiver_id;
            $msg->msg = $request->msg;
            $msg->conversation_id = json_encode(session()->get('id'),);
            $msg->type = 'single';
            $msg->save();
        }else{
            // selected with single user conversation stored
            $conversation_id = array(session()->get('id'),intval($request->receiver_id));
            $msg->sender = session()->get('id');
            $msg->receiver = $request->receiver_id;
            $msg->msg = $request->msg;
            $msg->conversation_id = json_encode($conversation_id);
            $msg->type = 'single';
            $msg->save();
        }
        $data = array( 'status' => true , 'msg' => $request->msg );
        return $data;
    }
    public function get_all_message(Request $request)
    {
        if($request->user_id != session()->get('id'))
        {
            $all_messages = chat::where('conversation_id','like', '%'.session()->get('id').'%')
                                ->where('conversation_id','like', '%'.$request->user_id.'%')
                                ->get();
            $messages = $all_messages->toArray();

            if(!empty($messages))
            {
                $html = '';
                foreach($messages as $data)
                {
                    if($data['sender'] == session()->get('id')){
                        $html .= '<div style="justify-content: end; align-items: end;">';
                        $html .= '     <span style="border-radius: 8px 8px 0px 8px;">'.$data['msg'].'</span>';
                        $html .= '     <span style="border-radius: 50%;height: 20px; width: 0%; font-size: 8px; margin-left: 5px;display: flex; justify-content: center;"> US </span>';
                        $html .= '</div>';
                    }else{
                        $html .= '<div style="display: flex; align-items: end;">';
                        $html .= '     <span style="margin-right: 5px; border-radius: 50%;height: 20px; width: 0%; font-size: 8px; margin-left: 5px;display: flex; justify-content: center;"> US </span>';
                        $html .= '     <span>'.$data['msg'].'</span>';
                        $html .= '</div>'; 
                    }
                }
            }else{
                $html .= '<div class="message_not_found_error w-100 h-100 d-flex justify-content-center align-items-center">';
                $html .= '  <h3 style="color: #b7b7b7;background:none;">No messages found.</h3>';
                $html .= '</div>';
            }
            return array('status' => true,'messages' => $html);
        }
        else
        {
            $all_messages = chat::where('conversation_id','['.session()->get('id').',0]')->get();
            $messages = $all_messages->toArray();

            if(!empty($messages))
            {
                $html = array();
                foreach($messages as $data)
                {
                    $html[] .= '<div style="justify-content: end;"><span style="border-radius: 8px 8px 0px 8px">'.$data['msg'].'</span></div>';
                }
            }else{
                $html .= '<div class="message_not_found_error w-100 h-100 d-flex justify-content-center align-items-center">';
                $html .= '  <h3 style="color: #b7b7b7;background:none;">No messages found.</h3>';
                $html .= '</div>';
            }
            return array('status' => true,'messages' => $html);
        }
    }
    function invite_user(Request $request)
    {
        print_r($request->all());exit;

        // $invite = new invite_data();

        // $invite->sender = 
    }
}
