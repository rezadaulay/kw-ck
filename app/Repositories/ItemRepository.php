<?php

namespace App\Repositories;

use App\Interfaces\ItemInterface;
use App\Events\ItemUpdated;

use App\Item;
use App\Checklist;

class ItemRepository implements ItemInterface
{
    protected $model;
    protected $checklist;

    public function __construct(Item $model, Checklist $checklist)
    {
        $this->model = $model;
        $this->checklist = $checklist;
    }

    public function paginate($checklistId, $params) {
        $page_offset = $params->has('page_offset') ? $params->page_offset : 0;
        $page_limit = $params->has('page_limit') ? $params->page_limit : 10;

        $data = $this->model->where('checklist_id', $checklistId);
        if ($params->has('sort')) {
            $data = $data->orderBy(str_replace('-' ,'', $params->sort), $params->sort[0] === '-' ? 'desc' : 'asc');
        }
        if ($params->has('filter') && in_array((key($params->filter)), ['description', 'is_completed', 'completed_at'])) {
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
                'description' => $d->description,
                'is_completed' => $d->is_completed,
                'completed_at' => $d->completed_at,
                'due' => $d->due,
                'urgency' => $d->urgency,
                'updated_by' => $d->updated_by,
                'assignee_id' => $d->assignee_id,
                'task_id' => $d->task_id,
                'created_at' => $d->created_at,
                'updated_at' => $d->updated_at
            ];
            $content[] = [
                'type'=> 'items',
                'id'=> $d->id,
                'attributes' => $attributes,
                'links' => url("checklists/checklists/$d->checklist_id/items/$d->id")
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

    public function create($checklistId, $attributes) {
        $checklist = $this->checklist->findOrFail($checklistId);
        $data = $checklist->items()->create([
            'description' => $attributes['description'],
            'due' => $attributes['due'],
            'urgency' => $attributes['urgency'],
            'updated_by' => $attributes['updated_by'],
            'assignee_id' => $attributes['assignee_id'],
            'task_id' => $attributes['task_id'],
        ]);
        return $this->find($data->id);
    }

    public function find($id)
    {
        $data = $this->model->findOrFail($id);
        $attributes = [
            'description' => $data->description,
            'is_completed' => $data->is_completed,
            'completed_at' => $data->completed_at,
            'due' => $data->due,
            'urgency' => $data->urgency,
            'updated_by' => $data->updated_by,
            'assignee_id' => $data->assignee_id,
            'task_id' => $data->task_id,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
        return [
            'data' => [
                'type'=> 'items',
                'id'=> $data->id,
                'attributes' => $attributes,
                'links' => url("checklists/checklists/$data->checklist_id/items/$id")
            ]
        ];
    }

    public function update($id, $attributes) {
        $data = $this->model->findOrFail($id);
        $data->update([
            'description' => $attributes['description'],
            'due' => isset($attributes['due']) ? $attributes['due'] : $data->due,
            'description' => $attributes['description'],
            'urgency' => isset($attributes['urgency']) ? $attributes['urgency'] : $data->urgency,
            'updated_by' => isset($attributes['updated_by']) ? $attributes['updated_by'] : $data->updated_by,
            'assignee_id' => isset($attributes['assignee_id']) ? $attributes['assignee_id'] : $data->assignee_id,
            'task_id' => isset($attributes['task_id']) ? $attributes['task_id'] : $data->task_id
        ]);
        return $this->find($data->id);
    }

    public function setComplete($items, $complete = true) {
        $response = [];
        foreach ($items as $item) {
            $data = $this->model->findOrFail($item['item_id']);
            $data->update([
                'is_completed' => !!$complete,
                'completed_at' => $complete ? date('Y-m-d H:i:s') : null
            ]);
            $response[] = [
                'id' => $data->id,
                'item_id' => $data->id,
                'is_completed' => $data->is_completed,
                'checklist_id' => $data->checklist_id,
            ];
            event(new ItemUpdated($data));
        }
        return $response;
    }

    public function delete($id) {
        $data = $this->model->findOrFail($id);
        $data->delete();
    }
}