<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ChecklistInterface;

use Auth;

class ChecklistController extends Controller
{
    private $model;
    public function __construct(ChecklistInterface $model)
    {
        $this->model = $model;
    }
    /*
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        return response()->json($this->model->paginate($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data.attributes.object_domain' => 'required',
            'data.attributes.object_id' => 'required',
            'data.attributes.description' => 'required',
            // 'data.attributes.is_completed' => 'nullable|boolean',
            // 'data.attributes.completed_at' => 'nullable|date_format:Y-m-d H:i:s',
            'data.attributes.due' => 'nullable|date_format:Y-m-d H:i:s'
        ]);
        $attributes = $request->data['attributes'];
        $data = $this->model->create([
            'object_domain' => $attributes['object_domain'],
            'object_id' => $attributes['object_id'],
            'description' => $attributes['description'],
            // 'is_completed' => isset($attributes['is_completed']) ? $attributes['is_completed'] : 0,
            // 'completed_at' => isset($attributes['is_completed']) ? $attributes['completed_at'] : null,
            'updated_by' => Auth::user()->name,
            'due' => isset($attributes['due']) ? $attributes['due'] : null,
            'urgency' => isset($attributes['urgency']) ? $attributes['urgency'] : null
        ]);
        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return response()->json($this->model->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'data.attributes.object_domain' => 'required',
            'data.attributes.object_id' => 'required',
            'data.attributes.description' => 'required',
            // 'data.attributes.is_completed' => 'nullable|boolean',
            // 'data.attributes.completed_at' => 'nullable|date_format:Y-m-d H:i:s',
            'data.attributes.due' => 'nullable|date_format:Y-m-d H:i:s'
        ]);
        $attributes = $request->data['attributes'];
        $data = $this->model->update($id, [
            'object_domain' => $attributes['object_domain'],
            'object_id' => $attributes['object_id'],
            'description' => $attributes['description'],
            // 'is_completed' => isset($attributes['is_completed']) ? $attributes['is_completed'] : 0,
            // 'completed_at' => isset($attributes['is_completed']) ? $attributes['completed_at'] : null,
            'updated_by' => Auth::user()->name,
            'due' => isset($attributes['due']) ? $attributes['due'] : null,
            'urgency' => isset($attributes['urgency']) ? $attributes['urgency'] : null
        ]);
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = $this->model->delete($id);
        return response()->json(null, 204);
    }
}
