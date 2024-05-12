<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Mail\SendEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendEmailAddBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Notifications\SendEmailAddBooks;
use App\Http\Requests\Book\updatebookReqest;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Book\StroreBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $books=Cache::remember('books', 60, function () {

       return  book::with(['authors'=>function($q){
            $q->wherePivot('available', true);
        }])->get();
    });
        return response()->json([
          'status' =>'all',
          'book' =>$books,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StroreBookRequest $request)
    {
        //
        try{


            DB::beginTransaction();
            //check
            $description=Null;
            $genre="null";

                 $path = null;

            if($request->hasfile('img'))
            {
                $extension = $request->file('img')->getClientOriginalExtension();
                $filename = Str::random(20).'.'.$extension;
                $path = $request->file('img')->storeAs('book', $filename, 'public');
                    $img = $path;

            }

             $name=$request->name;
             $description=$request->description;
             $price=$request->price;
             $quantity=$request->quantity;
             $genre=$request->genre;
//if short to check is null

               $description=$description !=null ? $request->description:null;
               $genre=$genre !=null ? $request->genre:null;
               $img=$path !=null ? $path:null;
               $price=$price !=null ? $request->price:null;

             $book=Book::create([
                'name'=>$name,
                'description'=>$description,
                'price'=>$price,
                'img'=>$path,
                'quantity'=>$quantity,
                'genre'=>$genre,
             ]);
             if($request->author_id)
             {
                $book->authors()->attach($request->author_id,['available'=>$request->available]);
             }
             DB::commit();
             $user=User::all();
             //send Email notifaction by queue
             Notification::send($user,new SendEmailAddBooks($book));
             return response()->json([
                'status'=>"Add Book",
                'book'=>$book,
                'author'=>$book->authors,
             ]);
        }
        catch(Throwable $th)
        {
            DB::rollback();
            Log::debug($th);
            Log::error($th->getMessage());
            return response()->json([
                'status'=>'error',
            ]);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        //
        $book=Book::find($id);

        return response()->json([
            'status' =>'show',
            'book' =>$book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updatebookReqest $request,  $id)
    {
        //
        try{
            $book=Book::find($id);
            DB::beginTransaction();
        $newData=[];
         if ($request->hasFile('img')) {
            $extension = $request->file('img')->getClientOriginalExtension();
            $filename = Str::random(20) . '.' . $extension;
            $path = $request->file('img')->storeAs('book', $filename, 'public');


        }
        if(isset($request->name)){
          $newData['name']=$request->name;
        }

          if(isset($request->img))
          {
            $newData['img']=$path;
          }
          if(isset($request->price))
          {
            $newData['price']=$request->price;
          }
          if(isset($request->quantity))
          {
            $newData['quantity']=$request->quantity;
          }
          if(isset($request->genre))
          {
            $newData['genre']=$request->genre;
          }
          if(isset($request->description))
          {
            $newData['description']=$request->description;
          }
       //if update author_id
       if($request->author_id){
        $book->authors()->sync($request->author_id,['available'=>$request->available]);
       }
          $book->update($newData);
          DB::commit();
     return response()->json([
         'status'=>'update',
            'book'=>$book,
            'author'=>$book->authors,
 ]);
        }
        catch(Throwable $th){
            DB::rollback();
            Log::debug($th);
            Log::error($th->getMessage());
            return response()->json([
                'status'=>'error',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        $book=Book::find($id);

        if($book){
            $authors=$book->authors;
            if($authors){
$book->authors()->detach();
            }
            $book->delete();
            return response()->json([
                'status'=>'delete'
            ]);
        }

    }
    public function filter()
    {
        $book=book::with(['authors'=>function($q){
            $q->where('available',1)->get();
        }])->get();
        return response()->json([
            'status'=>'list',
            'book'=>$book,
        ]);
    }
}
