<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todoLists = TodoList::all();
        return response([
            'todolist' => TodoListResource::collection($todoLists),
            'message' => 'Successful'
        ], 200);
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'task' => 'required|string',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Vaidation Error']);
        }

        $data['slug'] = Str::slug($request->task, '-');

        $todoList = TodoList::create($data);

        return response([
            'todolist' => new TodoListResource($todoList),
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $todoList)
    {
        return response(['todolist' => new TodoListResource($todoList), 'message' => 'Success'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function edit(TodoList $todoList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TodoList $todoList)
    {
        $request->slug = Str::slug($request->task, '-');
        $todoList->update($request->all());

        return response(['todolist' => new TodoListResource($todoList), 'message' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoList $todoList)
    {
        $todoList->delete();

        return response(['message' => 'task deleted']);
    }
}
