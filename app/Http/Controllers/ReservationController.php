<?php

namespace App\Http\Controllers;

use Auth;
use App\models\Book;
use Illuminate\Http\Request;
use App\models\reservation_Pivot;
use App\Jobs\sendEmailBorrowEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\UpdateQuantityAction;

class ReservationController extends Controller
{
    //
    public function borrow(Request $request,$id)
    {
        try{
            DB::beginTransaction();
        $book=Book::find($id);
        if($book->quantity > 0)
        {
            $user_id=Auth::user()->id;

         $book->borrow_User()->attach($user_id,
      [
            'borrow_date'=>\App\Helpers\formatDate(),

            'status'=>'Borrowed',
    ]);
   $borrow=reservation_Pivot::where('book_id',$id)->select('status');

//change Quantity
 event(new UpdateQuantityAction($book,$borrow));
 //send email
     sendEmailBorrowEmail::dispatch($book);
DB::commit();
return response()->json([
    'status'=>'تم الاستعارة',
      'book'=>$book,
]);
        }
        return response()->json([
            'status'=>'الكمية غير متوفرة ',
        ]);
    }


        catch(\Throwable $th)
{
    DB::rollback();
    Log::debug($th);
    Log::error($th->getMessage());
    return response()->json([
        'status'=>'error',
    ]);
}
    }
    //return book
    public function returnBook(Request $request,$id)
    {
        try{
            DB::beginTransaction();
        $borrow=reservation_Pivot::find($id);
         $user_id=$borrow->user_id;
         $book_id=$borrow->book_id;
        if(Auth::user()->id == $user_id)

        if($borrow->status="Borrowed")
        {

              $borrow->update([
                'status'=>'Returned',
                'return_date'=>\App\Helpers\formatDate(),
              ]);
              $borrow->delete();
              $book=Book::find($book_id);
              event(new UpdateQuantityAction($book, $borrow));
DB::commit();


           return response()->json([
          'status'=>'تم ارجاع الكتاب',
          'borrow'=>$borrow,
         'book'=> $book=$borrow->book_id,
           ]);
        }

        return response()->json([
            'status'=>'لايمكن unozartion'
             ]);
            }
            catch(\Throwable $th)
            {
                DB::rollback();
                Log::debug($th);
                Log::error($th->getMessage());
                return response()->json([
                    'status'=>'error',
                ]);
            }

    }


}
