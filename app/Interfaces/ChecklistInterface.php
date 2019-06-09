<?php

namespace App\Interfaces;

interface ChecklistInterface
{
    public function paginate($attributes);
    public function create($attributes);
    public function find($id);
    public function update($id, $attributes);
    public function delete($id);
}