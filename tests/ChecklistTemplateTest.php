<?php

use App\ChecklistTemplate;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTemplateTest extends TestCase
{
    public function test_can_create_template() {
        $data = [
            'name' => 'foo template',
            'checklist' => [
                'description'=> 'my checklist',
                'due_interval'=> 3,
                'due_unit'=> 'hour'
            ],
            'items' => [
                [
                    'description'=> 'my foo item',
                    'urgency'=> 2,
                    'due_interval'=> 40,
                    'due_unit'=> 'minute'
                ],
                [
                    'description'=> 'my bar item',
                    'urgency'=> 3,
                    'due_interval'=> 30,
                    'due_unit'=> 'minute'
                ]
            ]
        ];
        $response = $this->jsonRequest('POST', '/checklists/templates', ['data' => $data]);
        $this->assertEquals(201, $response->status());
    }

    public function test_can_update_template() {
        $template = factory(ChecklistTemplate::class)->create();
        $data = [
            'name' => 'foo template',
            'checklist' => [
                'description'=> 'my checklist',
                'due_interval'=> 3,
                'due_unit'=> 'hour'
            ],
            'items' => [
                [
                    'description'=> 'my foo item',
                    'urgency'=> 2,
                    'due_interval'=> 40,
                    'due_unit'=> 'minute'
                ],
                [
                    'description'=> 'my bar item',
                    'urgency'=> 3,
                    'due_interval'=> 30,
                    'due_unit'=> 'minute'
                ]
            ]
        ];
        $response = $this->jsonRequest('PATCH', '/checklists/templates/'.$template->id, ['data' => $data]);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_show_template() {
        $template = factory(ChecklistTemplate::class)->create();
        $response = $this->jsonRequest('GET', '/checklists/templates/' . $template->id);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_delete_template() {
        $template = factory(ChecklistTemplate::class)->create();
        $response = $this->jsonRequest('DELETE', '/checklists/templates/' . $template->id);
        $this->assertEquals(204, $response->status());
    }
}
