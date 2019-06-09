<?php

namespace App\Repositories;

use App\Interfaces\ChecklistTemplateInterface;

use App\ChecklistTemplate;

class ChecklistTemplateRepository implements ChecklistTemplateInterface
{
    protected $model;

    public function __construct(ChecklistTemplate $model)
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
        if ($params->has('filter') && in_array((key($params->filter)), ['name'])) {
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

        return [
            'meta' => [
                'count' => $count,
                'total' => $total
            ],
            'links' => [
                'first' => url("checklists/templates?page[limit]=$page_limit&page[offset]=0"),
                'last' => url("checklists/templates?page[limit]=$page_limit&page[offset]=".($total-1)),
                'next' => $page_offset < ($total-1) ? url("checklists/templates?page[limit]=$page_limit&page[offset]=".($page_offset+1)) : null,
                'prev' => $page_offset ? url("checklists/templates?page[limit]=$page_limit&page[offset]=".($page_offset-1)) : null
            ],
            'data' => $data->offset($page_offset)->limit($page_limit)->get(['id', 'name', 'checklist', 'items'])
        ];
    }

    public function create($attributes) {
        $data = $this->model->create([
            'name' => $attributes['name'],
            'checklist' => $attributes['checklist'],
            'items' => $attributes['items']
        ]);
        return $this->find($data->id);
    }

    public function find($id, $withDomains = false)
    {
        $data = $this->model->findOrFail($id);
        $attributes = [
            'name' => $data->name,
            'checklist' => $data->checklist,
            'items' => $data->items
        ];
        if ($withDomains) {
            $attributes['domains'] = $data->domains;
        }
        return [
            'data' => [
                'type'=> 'templates',
                'id'=> $data->id,
                'attributes' => $attributes,
                'links' => url('checklists/templates/' . $id)
            ]
        ];
    }

    public function update($id, $attributes) {
        $data = $this->model->findOrFail($id);
        $data->update([
            'name' => $attributes['name'],
            'checklist' => $attributes['checklist'],
            'items' => $attributes['items']
        ]);
        return $this->find($data->id);
    }

    // public function showDomains($templateId)
    // {
    //     $data = $this->model->findOrFail($templateId);
    //     return [
    //         'data' => $data->domains
    //     ];
    // }

    public function assignDomains($templateId, $attributes) {
        $data = $this->model->findOrFail($templateId);
        $data->update([
            'domains' => $attributes['domains']
        ]);
        return $this->find($data->id, true);
    }

    public function delete($id) {
        $data = $this->model->findOrFail($id);
        $data->delete();
    }
}