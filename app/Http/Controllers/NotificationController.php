<?php

namespace App\Http\Controllers;

use Auth;
use App\Helpers\formatDate;
use App\Models\Book;
use App\Models\Notifications;
use App\models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    //get notification unread
    public function index()
    {
       $notification= auth()->user()->unreadnotifications;
       foreach($notification as $notificate)
       {
        $notificate->data['name'];

       }
       return response()->json([
         'status'=>'list Notification',
         'notification' =>$notification,
       ]);
    }
    //readNotification
    public function ReadNotification($id)
    {
        $book=Book::find($id);
        $now=\App\Helpers\formatDate();
        $getId = DB::table('notifications')->where('data->book_id', $id)->pluck('id')->first();
        DB::table('notifications')->where('id',$getId)->update([
                 'read_at'=>$now
              ]);


     return response()->json([
        'notification'=>'read ',

     ]);
    }

}
