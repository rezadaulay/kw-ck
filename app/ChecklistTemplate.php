<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'checklist',
        'items',
        'domains'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'checklist' => 'object',
        'items' => 'array',
        'domains' => 'array'
    ];
}
