<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'checklist_id',
        'description',
        'is_completed',
        'completed_at',
        'due',
        'urgency',
        'updated_by',
        'assignee_id',
        'task_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'checklist_id' => 'integer',
        'description' => 'string',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime:c',
        'due' => 'datetime:c',
        'urgency' => 'integer',
        'updated_by' => 'string',
        'assignee_id' => 'integer',
        'task_id' => 'integer',
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c'
    ];

    /**
     * Get the checklist that owns the item.
     */
    public function checklist()
    {
        return $this->belongsTo('App\Checklist');
    }
}
