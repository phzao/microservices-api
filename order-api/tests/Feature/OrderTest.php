<?php

namespace Tests\Feature;

use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class OrderTest
 * @package Tests\Feature
 *
 * Success
 * testSuccessCreateOrderWithTotalValue()
 */
class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fails Create
     * testFailCreateOrderWithDescriptionWrong()
     * testFailCreateOrderWithUserIDWrong()
     * testFailCreateUserWithQuantityWrong()
     * testFailCreateOrderWithPriceWrong()
     */
    public function testFailCreateOrderWithDescriptionWrong()
    {
        $templateBody["user_id"] = 1;

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_description" => [
                    "The item description field is required."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_description'] = "hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad ";

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $templateMessage['errors']['item_description'] = ["The item description may not be greater than 250 characters."];
        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateOrderWithUserIDWrong()
    {
        $templateBody["item_description"] = "Teste";

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "user_id" => [
                    "The user id field is required."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['user_id'] = "a";

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $templateMessage['errors']['user_id'] =["The user id must be an integer."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateUserWithQuantityWrong()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item",
            "item_quantity"    => "a"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_quantity" => [
                    "The item quantity must be a number."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_quantity'] = -1;

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $templateMessage['errors']['item_quantity'] =["The item quantity must be at least 0."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_quantity'] = "9999999999999999";

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);
        $templateMessage['errors']['item_quantity'] =["The item quantity may not be greater than 999999999999999."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateOrderWithPriceWrong()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item",
            "item_price"    => "a"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_price" => [
                    "The item price must be a number."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_price'] = -1;

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $templateMessage['errors']['item_price'] =["The item price must be at least 0."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_price'] = "9999999999999999";

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);
        $templateMessage['errors']['item_price'] =["The item price may not be greater than 999999999999999."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testSuccessCreateOrderWithTotalValue()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item",
            "item_price"       => "10",
            "item_quantity"    => "5"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response->assertStatus(201);

        $first_order = $response->original["data"];

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(1, $first_order->id);
        $this->assertEquals(50, $first_order->total_value);

        $templateBody2 = [
            "user_id"          => "2",
            "item_description" => "New2",
            "item_price"       => "10"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody2);

        $response->assertStatus(201);

        $first_order = $response->original["data"];

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(2, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

        $templateBody2 = [
            "user_id"          => "3",
            "item_description" => "New2",
            "item_quantity"    => "10"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody2);

        $response->assertStatus(201);

        $first_order = $response->original["data"];

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(3, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

    }

    /**
     * Update Fails
     */
    public function testOrderUpdateDescriptionFail()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response->assertStatus(201);

        $templateBodyUpdate["item_description"] = null;

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_description" => [
                    "The item description field is required."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBodyUpdate['item_description'] = "hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad hiajsldjfslajdfaslf sad lsdjf asldjkfasl slkad falsjdf asdfsad ";

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_description" => [
                    "The item description may not be greater than 250 characters."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testOrderUpdateUserIDFail()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response->assertStatus(201);

        $templateBodyUpdate["user_id"] = null;

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "user_id" => [
                    "The user id field is required."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBodyUpdate['user_id'] = "a";

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage["errors"] = [
            "user_id" => [ "The user id must be an integer." ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testOrderUpdateItemQuantityFail()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response->assertStatus(201);

        $templateBodyUpdate["item_quantity"] = "a";

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_quantity" => [
                    "The item quantity must be a number."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBodyUpdate['item_quantity'] = -1;

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage["errors"] = [
            "item_quantity" => ["The item quantity must be at least 0."]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_quantity'] = "9999999999999999";

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);
        $templateMessage['errors']['item_quantity'] =[ "The item quantity may not be greater than 999999999999999."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testOrderUpdateItemPriceFail()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response->assertStatus(201);

        $templateBodyUpdate["item_price"] = "a";

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "item_price" => [
                    "The item price must be a number."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBodyUpdate['item_price'] = -1;

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBodyUpdate);

        $templateMessage["errors"] = [
            "item_price" => [ "The item price must be at least 0."]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['item_price'] = "9999999999999999";

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);
        $templateMessage['errors']['item_price'] =[ "The item price may not be greater than 999999999999999."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    /**
     * Delete fails
     */
    public function testOrderDeleteFail()
    {
        $templateBody = [
            "user_id"          => "4",
            "item_description" => "New item"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "ID must be a integer!",
            "errors"  => []
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $response->assertStatus(201);

        $response = $this->json('DELETE', 'api/v1/orders/a');

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);


        $response = $this->json('PUT', 'api/v1/orders/-1');

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    /**
     * List fails
     */
    public function testOrderListFails()
    {
        $templateMessage = [
            "status"  => "fail",
            "message" => "ID must be a integer!",
            "errors"  => []
        ];

        $response = $this->json('GET', 'api/v1/orders/a');

        $response
            ->assertStatus(422)
            ->json($templateMessage);

        $response = $this->json('GET', 'api/v1/orders/-1');

        $response
            ->assertStatus(422)
            ->json($templateMessage);
    }

    public function testOrderByUserListFails()
    {
        $templateMessage = [
            "status"  => "fail",
            "message" => "ID must be a integer!",
            "errors"  => []
        ];

        $response = $this->json('GET', 'api/v1/orders/user/a');

        $response
            ->assertStatus(422)
            ->json($templateMessage);

        $response = $this->json('GET', 'api/v1/orders/user/-1');

        $response
            ->assertStatus(422)
            ->json($templateMessage);

        $response = $this->json('GET', 'api/v1/orders/user/');

        $response
            ->assertStatus(422)
            ->json($templateMessage);
    }

    /**
     * List success
     */
    public function testOrderListSuccess()
    {
        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $first_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(1, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

        $response = $this->json('GET', 'api/v1/orders/1');

        $templateMessage = [
            "status" => "success",
            "data"   => [
                "id"               => "1",
                "user_id"          => "1",
                "item_description" => "New item",
                "item_quantity"    => null,
                "item_price"       => null,
                "total_value"      => null,
                "deleted_at"       => null,
                "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
                "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
            ]
        ];

        $response->assertStatus(200);
        $response->json($templateMessage);

        $templateBody = [
            "user_id"          => "2",
            "item_description" => "New item2"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $second_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $second_order);
        $this->assertEquals(2, $second_order->id);
        $this->assertEquals(null, $second_order->total_value);

        $response = $this->json('GET', 'api/v1/orders');

        $templateMessage = [
            "status" => "success",
            "data"   => [
                [
                    "id"               => "1",
                    "user_id"          => "1",
                    "item_description" => "New item",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
                ],
                [
                    "id"               => "2",
                    "user_id"          => "2",
                    "item_description" => "New item2",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $second_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $second_order->updated_at->format('Y-m-d H:i:s')
                ]
            ]
        ];

        $this->assertCount(2, $templateMessage["data"]);

        $response->assertStatus(200);
        $response->json($templateMessage);
    }

    public function testOrderByUserListSuccess()
    {
        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $first_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(1, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item2"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $second_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $second_order);
        $this->assertEquals(2, $second_order->id);
        $this->assertEquals(null, $second_order->total_value);

        $templateBody = [
            "user_id"          => "3",
            "item_description" => "New it"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $third_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $third_order);
        $this->assertEquals(3, $third_order->id);
        $this->assertEquals(null, $third_order->total_value);

        $response = $this->json('GET', 'api/v1/orders/user/1');

        $templateMessage = [
            "status" => "success",
            "data"   => [
                [
                    "id"               => "1",
                    "user_id"          => "1",
                    "item_description" => "New item",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
                ],
                [
                    "id"               => "2",
                    "user_id"          => "1",
                    "item_description" => "New item2",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $second_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $second_order->updated_at->format('Y-m-d H:i:s')
                ],
            ]
        ];

        $response->assertStatus(200);
        $response->json($templateMessage);
    }

    /**
     * Delete Success
     */
    public function testDeleteSuccess()
    {
        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $first_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(1, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item2"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $second_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $second_order);
        $this->assertEquals(2, $second_order->id);
        $this->assertEquals(null, $second_order->total_value);

        $templateBody = [
            "user_id"          => "3",
            "item_description" => "New it"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $third_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $third_order);
        $this->assertEquals(3, $third_order->id);
        $this->assertEquals(null, $third_order->total_value);

        $response = $this->json('GET', 'api/v1/orders/user/1');

        $templateMessage = [
            "status" => "success",
            "data"   => [
                [
                    "id"               => "1",
                    "user_id"          => "1",
                    "item_description" => "New item",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
                ],
                [
                    "id"               => "2",
                    "user_id"          => "1",
                    "item_description" => "New item2",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $second_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $second_order->updated_at->format('Y-m-d H:i:s')
                ],
            ]
        ];

        $response
            ->assertStatus(200)
            ->json($templateMessage);

        $response = $this->json('DELETE', 'api/v1/orders/2');

        $response->assertStatus(204);

        $response = $this->json('GET', 'api/v1/orders/user/1');

        $templateMessageAfter = [
            "status" => "success",
            "data"   => [
                [
                    "id"               => "1",
                    "user_id"          => "1",
                    "item_description" => "New item",
                    "item_quantity"    => null,
                    "item_price"       => null,
                    "total_value"      => null,
                    "deleted_at"       => null,
                    "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
                    "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
                ]
            ]
        ];

        $response
            ->assertStatus(200)
            ->json($templateMessageAfter);

    }

    /**
     * Create success
     */
    public function testCreateSuccess()
    {
        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $first_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(1, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

        $item = [
            "id"               => "1",
            "user_id"          => "1",
            "item_description" => "New item",
            "item_quantity"    => null,
            "item_price"       => null,
            "total_value"      => null,
            "deleted_at"       => null,
            "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
            "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
        ];

        $response->json($item);

        $templateBody2 = [
            "user_id"          => "2",
            "item_description" => "New ite2",
            "item_price"       => 10,
            "item_quantity"    => 5
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody2);

        $second_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $second_order);
        $this->assertEquals(2, $second_order->id);
        $this->assertEquals(50, $second_order->total_value);

        $item2 = [
            "id"               => "2",
            "user_id"          => "1",
            "item_description" => "New ite2",
            "item_quantity"    => 5,
            "item_price"       => 10,
            "total_value"      => 50,
            "deleted_at"       => null,
            "created_at"       => $second_order->created_at->format('Y-m-d H:i:s'),
            "updated_at"       => $second_order->updated_at->format('Y-m-d H:i:s')
        ];

        $response->json($item2);
    }

    /**
     * Update success
     */
    public function testUpdateSuccess()
    {
        $templateBody = [
            "user_id"          => "1",
            "item_description" => "New item"
        ];

        $response = $this->json('POST', 'api/v1/orders-test', $templateBody);

        $first_order = $response->original["data"];

        $response->assertStatus(201);

        $this->assertInstanceOf(Order::class, $first_order);
        $this->assertEquals(1, $first_order->id);
        $this->assertEquals(null, $first_order->total_value);

        $item = [
            "id"               => "1",
            "user_id"          => "1",
            "item_description" => "New item",
            "item_quantity"    => null,
            "item_price"       => null,
            "total_value"      => null,
            "deleted_at"       => null,
            "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
            "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
        ];

        $response->json($item);

        $templateBody2 = [
            "user_id"          => "3",
            "item_description" => "New ite2",
            "item_price"       => 10,
            "item_quantity"    => 5
        ];

        $response = $this->json('PUT', 'api/v1/orders/1', $templateBody2);

        $response->assertStatus(204);

        $item2 = [
            "id"               => "1",
            "user_id"          => "3",
            "item_description" => "New ite2",
            "item_quantity"    => 5,
            "item_price"       => 10,
            "total_value"      => 50,
            "deleted_at"       => null,
            "created_at"       => $first_order->created_at->format('Y-m-d H:i:s'),
            "updated_at"       => $first_order->updated_at->format('Y-m-d H:i:s')
        ];

        $search = $this->json('GET', 'api/v1/orders-test/1');
        $return = $search->original["data"];
        $obj    = $return->toArray();

        $this->assertEquals($obj, $item2);
    }
}
