<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $notes = $user->notes;

        return response()->json(compact('notes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:notes',
            'body'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $note = new Note();
        $note->title = $request->get('title');
        $note->body = $request->get('body');
        $note->user()->associate(auth()->user());
        $note->save();

        return response()->json(compact('note'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        $note = $user->notes()->find($id);

        return response()->json(compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => "required|max:255|unique:notes,title,{$id}",
            'body'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $note = Note::find($id);
        $note->title = $request->get('title');
        $note->body = $request->get('body');
        $note->save();

        return response()->json(compact('note'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
