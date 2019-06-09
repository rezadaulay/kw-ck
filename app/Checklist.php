<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    // public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'object_domain',
        'object_id',
        'description',
        'is_completed',
        'completed_at',
        'updated_by',
        'due',
        'urgency'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'object_domain' => 'string',
        'object_id' => 'string',
        'description' => 'string',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime:c',
        'updated_by' => 'string',
        'due' => 'datetime:c',
        'urgency' => 'integer',
        'created_at' => 'datetime:c',
        'updated_at' => 'datetime:c'
    ];

    /**
     * Get the items for the checklist.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
