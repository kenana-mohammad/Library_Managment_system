<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\models\Review;
use App\models\Book;
use App\models\Author;


class ReviewController extends Controller
{
    //Add Review to book
    public function AddReviewsToBook(Request $request,$book_id)
    {
        $book=Book::where('id',$book_id)->first();
        $book->reviews()->create([
         'user_id'=>Auth::user()->id,
         'Review'=>$request->Review,
        ]);
   return response()->json([
'status'=>'Add Review',
'review'=>$book->reviews,
      ]);
    }
    //Add Review To author
    public function AddReviewsToAuthor (Request $request,$author_id)
    {
       $author=Author::where('id',$author_id)->first();
       $author->reviews()->create([
        'Review'=>$request->Review,
        'user_id'=>Auth::user()->id,
       ]);
       return response()->json([
        'status'=>'Add Review',
        'review'=>$author->reviews,
              ]);
    }
    //list Of Reviews
    public function index()
    {
        $reviews=Review::all();
        return response()->json([
            'status'=>'list of reviews',
            'reviews'=>$reviews
        ]);
    }
    public function update(Request $request,$id)
    {
        $review=Review::find($id);
        $newData=[];
        if ($request->has('Review')) {
            $newData['Review'] = $request->Review;
        }

        if ($review->user_id === Auth::user()->id) {
            $review->update($newData);

            return response()->json([
                'status' => 'Review updated',
                'review' => $review,
            ]);
        }
        return response()->json([
            'status' => 'no allowed review another update',
        ]);

    }
        //delete
        public function Delete(Request $request,$id)
        {
            $review=Review::find($id);
            if($review->user_id == Auth::user()->id){
    $review->delete();
    return response()->json([
        'status'=>'delete'
    ]);
        }
        return response()->json([
            'status' => 'no allowed review another delete',
        ]);
        


    }
}
