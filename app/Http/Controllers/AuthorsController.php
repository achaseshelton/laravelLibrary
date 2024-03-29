<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorsResource;
use App\Http\Requests\AuthorsRequest;
use App\Models\BookAuthor;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AuthorsResource::collection(Author::all());
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
        $user = $request->user();
        // if user->role is less than 3 run this else unauthorized user
        if ($user->role_id < 3) {
        $faker = \Faker\Factory::create(1);
        $author = Author::create([
            'name' => $faker->name
        ]);

        return new AuthorsResource($author);
        } else {
            return 'user not authorized';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {

        return new AuthorsResource($author);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $user = $request->user();
        // if user->role is less than 3 run this else unauthorized user
        if ($user->role_id < 3) {
        $author->update([
            'name' => $request->input('name')
        ]);

        return new AuthorsResource($author);
        } else {
            return 'user not authorized';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Author $author)
    {
        // find the authors in the book author table where the author_id is equal to $author and delete them

        $user = $request->user();
        // if user->role is less than 3 run this else unauthorized user
        if ($user->role_id < 3) {
        $bookAuthors = BookAuthor::all()->where('author_id', '=', $author->id);
        foreach ($bookAuthors as $id => $bookAuthor) {
            $author = BookAuthor::find($bookAuthor['id']);
            $author->delete();
        }
        $author->delete();
        return response(null, 204);
        } else {
            return 'user not authorized';
        }
    }
}
