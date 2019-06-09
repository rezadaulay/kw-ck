<?php

use App\Checklist;
use App\Item;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{
    public function test_can_create_item() {
        $checklist = factory(Checklist::class)->create();
        $data = [
            'attribute' => [
                'description' => 'Need to verify this guy house.',
                'due' => '2019-01-19 18:34:51',
                'urgency' => '2',
                'assignee_id' => 123
            ]
        ];
        
        $response = $this->jsonRequest('POST', '/checklists/'.$checklist->id.'/items', ['data' => $data]);
        $this->assertEquals(201, $response->status());
    }

    public function test_can_update_item() {
        $checklist = factory(Checklist::class)->create();
        $item = $checklist->items()->save(factory(Item::class)->make());
        // $item = factory(Item::class)->create();
        $data = [
            'attribute' => [
                'description' => 'Need to verify this guy house.',
                'due' => '2019-01-19 18:34:51',
                'urgency' => '2',
                'assignee_id' => 123
            ]
        ];
        $response = $this->jsonRequest('PATCH', '/checklists/'.$item->checklist_id.'/items/'.$item->id, ['data' => $data]);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_show_item() {
        $checklist = factory(Checklist::class)->create();
        $item = $checklist->items()->save(factory(Item::class)->make());
        $response = $this->jsonRequest('GET', '/checklists/'.$item->checklist_id.'/items/'.$item->id);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_delete_item() {
        $checklist = factory(Checklist::class)->create();
        $item = $checklist->items()->save(factory(Item::class)->make());
        $response = $this->jsonRequest('DELETE', '/checklists/'.$item->checklist_id.'/items/'.$item->id);
        $this->assertEquals(204, $response->status());
    }

    public function test_can_complete_items() {
        $checklist = factory(Checklist::class)->create();
        $data = [];
        for ($i=0; $i < 10; $i++) {
            $item = $checklist->items()->save(factory(Item::class)->make());
            $data[] = [
                'item_id' => $item->id
            ];
        }
        $response = $this->jsonRequest('POST', '/checklists/complete', ['data' => $data]);
        $this->assertEquals(200, $response->status());
    }

    public function test_can_incomplete_items() {
        $checklist = factory(Checklist::class)->create();
        $data = [];
        for ($i=0; $i < 10; $i++) {
            $item = $checklist->items()->save(factory(Item::class)->make());
            $data[] = [
                'item_id' => $item->id
            ];
        }
        $response = $this->jsonRequest('POST', '/checklists/incomplete', ['data' => $data]);
        $this->assertEquals(200, $response->status());
    }
}
