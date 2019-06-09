<?php

use App\Checklist;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTest extends TestCase
{
    public function test_can_create_checklist() {
        $data = [
            'attributes' => [
                'object_domain' => 'contact',
                'object_id' => '1',
                'due' => '2019-01-25 07:50:14',
                'urgency' => 1,
                'description' => 'Need to verify this guy house.'
            ]
        ];
        // $user = factory('App\User')->create();
        // $this->actingAs($user)->json('POST', '/checklists', ['data' => $data])
        //      ->seeJson([
        //         'created' => true,
        //      ]);
        $response = $this->jsonRequest('POST', '/checklists', ['data' => $data]);
        $this->assertEquals(201, $response->status());
    }

    public function test_can_update_checklist() {
        $checklist = factory(Checklist::class)->create();
        $data = [
            'attributes' => [
                'object_domain' => 'contact',
                'object_id' => '1',
                'due' => '2019-01-25 07:50:14',
                'urgency' => 1,
                'description' => 'Need to verify this guy house.'
            ]
        ];
        $response = $this->jsonRequest('PATCH', '/checklists/'.$checklist->id, ['data' => $data]);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_show_checklist() {
        $checklist = factory(Checklist::class)->create();
        $response = $this->jsonRequest('GET', '/checklists/' . $checklist->id);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_delete_checklist() {
        $checklist = factory(Checklist::class)->create();
        $response = $this->jsonRequest('DELETE', '/checklists/' . $checklist->id);
        $this->assertEquals(204, $response->status());
    }
}
