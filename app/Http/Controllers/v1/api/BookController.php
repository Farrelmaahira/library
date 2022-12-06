<?php

namespace App\Http\Controllers\v1\api;
use App\Http\Controllers\v1\api\BaseController as ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Dotenv\Parser\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $book = Book::all();
        return $this->sendResponse(BookResource::collection($book), 'Book');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'released' => 'required',
            'image' => 'image'
        ]);
        //return $this->sendResponse($request, 'New Book has Been Added');
        // if($validate->fails()){
        //     return $this->sendError('Validation error', $validate->errors(), 400);
        // }    

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $image->storeAs('/img', $image->hashName());
            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'released' => $request->released,
                'image' => "img/" . $image->hashName()
            ]);
        }else{
            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'released' => $request->released,
            ]);
        }
        return $this->sendResponse(new BookResource($book), 'New Book has Been Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        return $this->sendResponse(new BookResource($book), 'Book with id '. $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        return $this->sendResponse(new BookResource($book), 'Here is ur book');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'released' => 'required',
            'image' => 'image'
        ]);

        if($validate->fails()){
            return $this->sendError('Validation errors', $validate->errors());
        }

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $image->storeAs('public/img', $image->hashName());
            $book = Book::where('id',$id)->update([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'released' => $request->released,
                'image' => $image->hashName()
            ]);

            return $this->sendResponse(new BookResource($book), 'Book has been updated');
        } else {
            $book = Book::where('id',$id)->update([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'released' => $request->released,
            ]);

            return $this->sendResponse(new BookResource($book), 'Book has been updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete();
        return $this->sendResponse(new BookResource($book), 'Book has been deleted');   
    }
}
