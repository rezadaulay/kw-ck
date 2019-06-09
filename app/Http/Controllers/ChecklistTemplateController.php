<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ChecklistTemplateInterface;

class ChecklistTemplateController extends Controller
{
    private $model;
    public function __construct(ChecklistTemplateInterface $model)
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
            'data.name' => 'required',
            'data.checklist.description' => 'required',
            'data.checklist.due_interval' => 'nullable|numeric',
            'data.checklist.due_unit' => 'nullable|in:minute,hour,day,week,month',
            'data.items.*.description' => 'required',
            'data.items.*.urgency' => 'nullable|numeric',
            'data.items.*.due_interval' => 'nullable|numeric',
            'data.items.*.due_unit' => 'nullable|in:minute,hour,day,week,month',
        ]);
        $data = $this->model->create([
            'name' => $request->data['name'],
            'checklist' => $request->data['checklist'],
            'items' => $request->data['items']
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
            'data.name' => 'required',
            'data.checklist.description' => 'required',
            'data.checklist.due_interval' => 'nullable|numeric',
            'data.checklist.due_unit' => 'nullable|in:minute,hour,day,week,month',
            'data.items.*.description' => 'required',
            'data.items.*.urgency' => 'nullable|numeric',
            'data.items.*.due_interval' => 'nullable|numeric',
            'data.items.*.due_unit' => 'nullable|in:minute,hour,day,week,month',
        ]);
        $data = $this->model->update($id, [
            'name' => $request->data['name'],
            'checklist' => $request->data['checklist'],
            'items' => $request->data['items']
        ]);
        return response()->json($data);
    }


    /**
     * Assign domains the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function assignDomains(Request $request, $id)
    {
        $this->validate($request, [
            'data.*.attributes.object_id' => 'required|numeric',
            'data.*.attributes.object_domain' => 'required'
        ]);
        $data = $this->model->assignDomains($id, ['domains' => $request->data]);
        return response()->json($data, 201);
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
