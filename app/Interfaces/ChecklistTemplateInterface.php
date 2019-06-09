<?php

namespace App\Interfaces;

interface ChecklistTemplateInterface
{
    public function paginate($attributes);
    public function create($attributes);
    public function find($id, $withDomains);
    public function update($id, $attributes);
    public function assignDomains($templateId, $attributes);
    // public function showDomains($templateId);
    public function delete($id);
}