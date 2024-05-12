<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Author\storeAuthorRequest;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $authors=Cache::remember('authors', 60, function () {
            return Author::all();

        });
        return response()->json([
            'status'=>'list of author',
            'author'=>$authors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeAuthorRequest $request)
    {
        //
        try
        {
         DB::beginTransaction();

           $author =Author::create([
            'name'=>$request->name,
           ]);
           DB::commit();
           return response()->json([
            'status' =>'Add Author',
            'author'=>$author,
           ]);

        }
        catch(\throwable $th)
        {
            DB::rolllback();
           Log::error($th->getMessage());
           Log::debug($th);
           return response()->json([
            'status'=>'error'
           ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        //
        $author=Author::find($id);
        return response()->json([
            'status'=>'show',
            'author'=>$author
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try{
            $author=Author::find($id);
          DB::beginTransaction();
          $newData=[];
          if(isset($request->name))
          {
            $newData['name']=$request->name;
          }
          $author->update($newData);
          DB::commit();
          return response()->json([
            'status'=>'update',
            'author'=>$author,
          ]);

        }
        catch(throwable $th)
        {
            DB::rolllback();
            Log::error($th->getMessage());
            Log::debug($th);
            return response()->json([
             'status'=>'error'
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $author=Author::find($id);
        if($author)
        {
            $books=$author->books;
            if($books){


            $author->books()->detach();}
            $author->delete();

        }
        return response()->json([
            'status'=>'delete'
        ]);
    }
}
