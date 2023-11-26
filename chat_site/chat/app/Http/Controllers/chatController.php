<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\chat;
use App\Models\invite_data;
use App\Models\Userdata;

class chatController extends Controller
{
    public function send_message(Request $request)
    {
        $login_user_id = session()->get('id');
        $data = array();
        $msg = new chat();
        $html = '';
        if(session()->get('id') == $request->receiver_id)
        {
            // login user self conversation stored
            $msg->sender = $login_user_id;
            $msg->receiver = $request->receiver_id;
            $msg->msg = $request->msg;
            $msg->conversation_id = json_encode($login_user_id,);
            $msg->type = 'single';
            $msg->save();
        }else{
            // selected with single user conversation stored
            $conversation_id = array($login_user_id,intval($request->receiver_id));
            $msg->sender = $login_user_id;
            $msg->receiver = $request->receiver_id;
            $msg->msg = $request->msg;
            $msg->conversation_id = json_encode($conversation_id);
            $msg->type = 'single';
            $msg->save();
        }

        $all_messages = chat::where('conversation_id','like', '%'.session()->get('id').'%')
                            ->where('conversation_id','like', '%'.$request->user_id.'%')
                            ->orderBy('created_at', 'DESC')
                            ->first();
        $messages = $all_messages->toArray();
        
        // message
        $html .= '<div class="msg_dropdown" msg_id="'.$messages['id'].'" style="justify-content: end; align-items: end;">';
        $html .= '     <span style="border-radius: 8px 8px 0px 8px;">'.$messages['msg'].'</span>';
        $html .= '     <span style="border-radius: 50%;height: 20px; width: 0%; font-size: 8px; margin-left: 5px;display: flex; justify-content: center;"> US </span>';
        $html .= '</div>';
        
        $data = array( 'status' => true , 'msg' => $html );
        return $data;
    }
    public function get_all_messages(Request $request)
    {
        $login_user_id = session()->get('id');
        $opposite_user_id = $request->user_id;
        $login_user_name = session()->get('name');
        $html = '';
        if($request->user_id != $login_user_id)
        {
            $all_messages = chat::where('conversation_id','like', '%'.$login_user_id.'%')
                                ->where('conversation_id','like', '%'.$opposite_user_id.'%')
                                ->get();
            $messages = $all_messages->toArray();

            if(!empty($messages))
            {
                foreach($messages as $data)
                {
                    if($data['sender'] == $login_user_id)
                    {
                        // message
                        $html .= '<div class="msg_dropdown" msg_id="'.$data['id'].'" style="cursor: pointer; justify-content: end; align-items: end;">';
                        $html .= '     <span class="msg_text" style="border-radius: 8px 8px 0px 8px;">'.$data['msg'].'</span>';
                        $html .= '     <span class="sender_side_avatar">'.substr($login_user_name,0,2).'</span>';
                        $html .= '</div>';
                    }
                    else
                    {
                        $opposite_user_data = Userdata::where('id',$opposite_user_id)->get();
                        $opt_user_data = $opposite_user_data->toArray();
                        if(!empty($opt_user_data))
                        {
                            // message
                            $html .= '<div class="msg_dropdown" msg_id="'.$data['id'].'" style="cursor: pointer; display: flex; align-items: end;">';
                            $html .= '     <span class="receiver_side_avatar">'.substr($opt_user_data[0]['user_name'],0,2).'</span>';
                            $html .= '     <span class="msg_text">'.$data['msg'].'</span>';
                            $html .= '</div>'; 
                        }
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
            // Self user messages
            $all_messages = chat::where('conversation_id','['.$login_user_id.',0]')->get();
            $messages = $all_messages->toArray();

            if(!empty($messages))
            {
                foreach($messages as $data)
                {
                    // message
                    $html .= '<div class="msg_dropdown" msg_id="'.$data['id'].'" style="justify-content: end;align-items:end;">';
                    $html .= '  <span style="border-radius: 8px 8px 0px 8px">'.$data['msg'].'</span>';
                    $html .= '  <span style="border-radius: 50%;height: 20px; width: 0%; font-size: 8px; margin-left: 5px;display: flex; justify-content: center;"> US </span>';
                    $html .= '</div>';
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
    function delete_message(Request $request)
    {
        $msg_id = $request->msg_id;
        $login_user_id = session()->get('id');
        $find = chat::where('sender',$login_user_id)->find($msg_id);
        $status = false;
        $opposite_user_id = '';
        if($find) {
            $opposite_user_id = $find['receiver'];
            $delete = $find->delete();
            if($delete) {
                $status = true;
            }
        }
        return array('status' => $status,'opposite_user_id' => $opposite_user_id);
    }
}
