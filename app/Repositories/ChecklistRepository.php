<?php

namespace App\Repositories;

use App\Interfaces\ChecklistInterface;

use App\Checklist;

class ChecklistRepository implements ChecklistInterface
{
    protected $model;

    public function __construct(Checklist $model)
    {
        $this->model = $model;
    }

    public function paginate($params) {
        $page_offset = $params->has('page_offset') ? $params->page_offset : 0;
        $page_limit = $params->has('page_limit') ? $params->page_limit : 10;

        $data = $this->model;
        if ($params->has('sort')) {
            $data = $data->orderBy(str_replace('-' ,'', $params->sort), $params->sort[0] === '-' ? 'desc' : 'asc');
        }
        if ($params->has('filter') && in_array((key($params->filter)), ['object_domain', 'object_id', 'description', 'is_completed', 'completed_at'])) {
            $search_type = key(array_values($params->filter)[0]);
            $field = key($params->filter);
            $keyword = array_values($params->filter)[0][$search_type];
            if (strpos($search_type, 'like') !== false) {
                if ($keyword[0] === '*') {
                    $keyword = '%'.$keyword;
                } else if (substr($keyword[0], -1) === '*') {
                    $keyword = $keyword.'%';
                } else {
                    $keyword = '%'.$keyword.'%';
                }
                $data = $data->where($field, $search_type === '!like' ? 'not like' : 'like', $keyword);
            }
            else if ($search_type === 'is') {
                $data = $data->where($field, $keyword);
            } else if ($search_type === '!is') {
                $data = $data->where($field, '!=', $keyword);
            } else if ($search_type === 'in') {
                $data = $data->whereIn($field, explode(',', $keyword));
            } else if ($search_type === '!in') {
                $data = $data->whereNotIn($field, explode(',', $keyword));
            }
        }
        $total = $data->count('id');
        $count = ceil($total/$page_limit);

        $content = [];
        foreach ($data->offset($page_offset)->limit($page_limit)->get() as $d) {
            $attributes = [
                'object_domain' => $d->object_domain,
                'object_id' => $d->object_id,
                'description' => $d->description,
                'is_completed' => $d->is_completed,
                'completed_at' => $d->completed_at,
                'updated_by' => $d->updated_by,
                'due' => $d->due,
                'urgency' => $d->urgency,
                'created_at' => $d->created_at,
                'updated_at' => $d->updated_at
            ];
            $content[] = [
                'type'=> 'checklists',
                'id'=> $d->id,
                'attributes' => $attributes,
                'links' => url('checklists/' . $d->id)
            ];
        }

        return [
            'meta' => [
                'count' => $count,
                'total' => $total
            ],
            // 'links' => [
            //     'first' => 'https://kong.command-api.kw.com/api/v1/checklists/templates?page[limit]=10&page[offset]=0',
            //     'last' => 'https://kong.command-api.kw.com/api/v1/checklists/templates?page[limit]=10&page[offset]=10',
            //     'next' => 'https://kong.command-api.kw.com/api/v1/checklists/templates?page[limit]=10&page[offset]=10',
            //     'prev' => null
            // ],
            'data' => $content
        ];
    }

    public function create($attributes) {
        $data = $this->model->create([
            'object_domain' => $attributes['object_domain'],
            'object_id' => $attributes['object_id'],
            'description' => $attributes['description'],
            // 'is_completed' => $attributes['is_completed'],
            // 'completed_at' => $attributes['completed_at'],
            'updated_by' => $attributes['updated_by'],
            'due' => $attributes['due'],
            'urgency' => $attributes['urgency']
        ]);
        return $this->find($data->id);
    }

    public function find($id)
    {
        $data = $this->model->findOrFail($id);
        $attributes = [
            'object_domain' => $data->object_domain,
            'object_id' => $data->object_id,
            'description' => $data->description,
            'is_completed' => $data->is_completed,
            'completed_at' => $data->completed_at,
            'updated_by' => $data->updated_by,
            'due' => $data->due,
            'urgency' => $data->urgency,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
        return [
            'data' => [
                'type'=> 'checklists',
                'id'=> $data->id,
                'attributes' => $attributes,
                'links' => url('checklists/' . $id)
            ]
        ];
    }

    public function update($id, $attributes) {
        $data = $this->model->findOrFail($id);
        $data->update([
            'object_domain' => $attributes['object_domain'],
            'object_id' => $attributes['object_id'],
            'description' => $attributes['description'],
            // 'is_completed' => isset($attributes['is_completed']) ? $attributes['is_completed'] : $data->is_completed,
            // 'completed_at' => isset($attributes['completed_at']) ? $attributes['completed_at'] : $data->completed_at,
            'updated_by' => isset($attributes['updated_by']) ? $attributes['updated_by'] : $data->updated_by,
            'due' => isset($attributes['due']) ? $attributes['due'] : $data->due,
            'urgency' => isset($attributes['urgency']) ? $attributes['urgency'] : $data->urgency
        ]);
        return $this->find($data->id);
    }

    public function delete($id) {
        $data = $this->model->findOrFail($id);
        $data->delete();
    }
}