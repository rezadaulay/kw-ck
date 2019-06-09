<?php

namespace App\Interfaces;

interface ItemInterface
{
    public function paginate($checklistId, $attributes);
    public function create($checklistId, $attributes);
    public function find($id);
    public function update($id, $attributes);
    public function setComplete($items);
    public function delete($id);
}