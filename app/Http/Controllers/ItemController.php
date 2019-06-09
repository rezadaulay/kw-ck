<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ItemInterface;

use Auth;

class ItemController extends Controller
{
    private $model;
    public function __construct(ItemInterface $model)
    {
        $this->model = $model;
    }
    /*
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param  int  $checklistId
     * @return Response
     */
    public function index(Request $request, $checklistId)
    {
        return response()->json($this->model->paginate($checklistId, $request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @param  int  $checklistId
     * @return Response
     */
    public function store(Request $request, $checklistId)
    {
        $this->validate($request, [
            'data.attribute.description' => 'required',
            'data.attribute.due' => 'nullable|date_format:Y-m-d H:i:s'
        ]);
        $attribute = $request->data['attribute'];
        $data = $this->model->create($checklistId, [
            'description' => $attribute['description'],
            'due' => isset($attribute['due']) ? $attribute['due'] : null,
            'urgency' => isset($attribute['urgency']) ? $attribute['urgency'] : null,
            'updated_by' => Auth::user()->name,
            'assignee_id' => isset($attribute['assignee_id']) ? $attribute['assignee_id'] : null,
            'task_id' => isset($attribute['task_id']) ? $attribute['task_id'] : null,
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
     * @param  int  $checklistId
     * @param  int  $id
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request, $checklistId, $id)
    {
        $this->validate($request, [
            'data.attribute.description' => 'required',
            'data.attribute.due' => 'nullable|date_format:Y-m-d H:i:s'
        ]);
        $attribute = $request->data['attribute'];
        $data = $this->model->update($id, [
            'description' => $attribute['description'],
            'due' => isset($attribute['due']) ? $attribute['due'] : null,
            'urgency' => isset($attribute['urgency']) ? $attribute['urgency'] : null,
            'updated_by' => Auth::user()->name,
            'assignee_id' => isset($attribute['assignee_id']) ? $attribute['assignee_id'] : null,
            'task_id' => isset($attribute['task_id']) ? $attribute['task_id'] : null,
        ]);
        return response()->json($data);
    }

    /**
     * Set complete items.
     *
     * @param  Request  $request
     * @return Response
     */
    public function setComplete(Request $request)
    {
        $this->validate($request, [
            'data.*.item_id' => 'required|exists:items,id'
        ]);
        $data = $this->model->setComplete($request->data);
        return response()->json($data);
    }

    /**
     * Set incomplete items.
     *
     * @param  Request  $request
     * @return Response
     */
    public function setIncomplete(Request $request)
    {
        $this->validate($request, [
            'data.*.item_id' => 'required|exists:items,id'
        ]);
        $data = $this->model->setComplete($request->data, false);
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
