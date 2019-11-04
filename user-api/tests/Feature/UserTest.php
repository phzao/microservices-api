<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserTest
 * @package Tests\Feature
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testFailCreateNewUserWithNameWrong()
    {
        $templateBody = [
            "cpf"   => "00011122233",
            "email" => "eu@tu"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "name" => [
                    "The name field is required."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['name'] = "hi";

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $templateMessage['errors']['name'] = ["The name must be at least 5 characters."];
        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['name'] = "ajsdflasdjflas lskdjfasjdflasjdf llsdjf lasdjflasjdflas ljssa ldfjasldjflasdjljflasdjflasd ljsfldkajfl";

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $templateMessage['errors']['name'] = ["The name may not be greater than 100 characters."];
        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateNewUserWithPhoneWrong()
    {
        $templateBody = [
            "cpf"          => "00011122233",
            "email"        => "eu@tu",
            "name"         => "Maria Madalena",
            "phone_number" => "055"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "phone_number" => [
                    "The phone number must be at least 10 characters."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['phone_number'] = "2332322323222";

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $templateMessage['errors']['phone_number'] =["The phone number may not be greater than 12 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateUserWithCPFWrong()
    {
        $templateBody = [
            "email"        => "eu@tu",
            "name"         => "Maria Madalena",
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "cpf" => [
                    "The cpf field is required."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['cpf'] = "0001";

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $templateMessage['errors']['cpf'] =["The cpf must be at least 11 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['cpf'] = "0001";

        $response = $this->json('POST', 'api/v1/users', $templateBody);
        $templateMessage['errors']['cpf'] =["The cpf must be at least 11 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateUserWithEmailWrong()
    {
        $templateBody = [
            "name"  => "Maria Madalena",
            "cpf" => "00011122233"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "email" => [
                    "The email field is required."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['email'] = "eu";

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $templateMessage['errors']['email'] =["The email must be a valid email address."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['email'] = "ajsdflasdjflaslskdjfasjdflasjdfllsdjflasdjflasjdflasljssaldfjasldjflasdjljflasdjflasdljsfldkajfl@eus.com.ajsdflasdjflaslskdjfasjdflasjdfllsdjflasdjflasjdflasljssaldfjasldjflasdjljflasdjflasdljsfldkajfl";

        $response = $this->json('POST', 'api/v1/users', $templateBody);
        $templateMessage['errors']['email'] =["The email may not be greater than 200 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testFailCreateUserWithoutData()
    {

        $response = $this->json('POST', 'api/v1/users', []);
        $response
            ->assertStatus(422)
            ->assertExactJson(
                [
                    "status"  => "fail",
                    "message" => "The given data was invalid.",
                    "errors"  => [
                        "name"  => [
                            "The name field is required."
                        ],
                        "cpf"   => [
                            "The cpf field is required."
                        ],
                        "email" => [
                            "The email field is required."
                        ]
                    ]
                ]
            );
    }

    public function testFailCreateUserWithDuplicateData()
    {
        $templateBody = [
            "cpf"          => "00011122233",
            "email"        => "eu@tu",
            "name"         => "Maria Madalena"
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response->assertStatus(201);

        $response = $this->json('POST', 'api/v1/users', $templateBody);
        $response
            ->assertStatus(422)
            ->assertExactJson(
                [
                    "status"  => "fail",
                    "message" => "The given data was invalid.",
                    "errors"  => [
                        "cpf"   => [
                            "The cpf has already been taken."
                        ],
                        "email" => [
                            "The email has already been taken."
                        ]
                    ]
                ]
            );

    }

    public function testUserDetailsFail()
    {
        $response = $this->json('GET', 'api/v1/users-test/1');

        $response
            ->assertStatus(200)
            ->assertExactJson(
                [
                    "status" => "success",
                    "data"   => []
                ]
            );

        $response = $this->json('GET', 'api/v1/users-test/');

        $response
            ->assertStatus(200)
            ->assertExactJson(
                [
                    "status" => "success",
                    "data"   => []
                ]
            );

        $response = $this->json('GET', 'api/v1/users-test/a');

        $response
            ->assertStatus(422)
            ->assertExactJson(
                [
                    "status"  => 'fail',
                    "message" => "ID must be a integer!",
                    "errors"  => []
                ]
            );
    }

    public function testUserDetailsSuccess()
    {
        $templateBody = [
            "cpf"   => "00011122233",
            "email" => "eu@tu",
            "name"  => "Maria Madalena"
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response->assertStatus(201);

        $response = $this->json('GET', 'api/v1/users-test/1');

        $data = $response->original["data"];

        $this->assertInstanceOf(User::class, $data);
        $this->assertEquals(1, $data->id);


        $templateMessage = [
            "status" => "success",
            "data"   => [
                "id"           => 1,
                "name"         => "Maria Madalena",
                "email"        => "eu@tu",
                "cpf"          => "00011122233",
                "phone_number" => null,
                "created_at"   => $data->getDateTimeStringFrom('created_at'),
                "updated_at"   => $data->getDateTimeStringFrom('updated_at'),
            ]
        ];

        $response
            ->assertStatus(200)
            ->assertExactJson($templateMessage);
    }

    public function testUserListSuccess()
    {
        $templateMessage = [
            "status" => "success",
            "data"   => []
        ];

        $response = $this->json('GET', 'api/v1/users-test');

        $response
            ->assertStatus(200)
            ->json($templateMessage);

        $templateBody = [
            "cpf"          => "00011122233",
            "email"        => "eu@tu",
            "name"         => "Maria Madalena"
        ];

        $response   = $this->json('POST', 'api/v1/users', $templateBody);
        $first_user = $response->original["data"];

        $this->assertInstanceOf(User::class, $first_user);
        $this->assertEquals(1, $first_user->id);

        $templateBody = [
            "cpf"          => "00011122231",
            "email"        => "eu@tus.com",
            "name"         => "Mario Madaleno"
        ];

        $response    = $this->json('POST', 'api/v1/users', $templateBody);
        $second_user = $response->original["data"];

        $this->assertInstanceOf(User::class, $second_user);
        $this->assertEquals(2, $second_user->id);

        $templateMessage = [
            "status" => "success",
            "data"   => [
                $first_user->getFullDetails(),
                $second_user->getFullDetails(),
            ]
        ];

        $response = $this->json('GET', 'api/v1/users-test');

        $response
            ->assertStatus(200)
            ->assertExactJson($templateMessage);
    }

    public function testUserAPIDetails()
    {
        $templateMessage = [
            "status"  => "fail",
            "message" => "You don't have permission to access this data."
        ];

        $response = $this->json('GET', 'api/v1/users-secure/1');

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody = [
            "cpf"   => "00011122233",
            "email" => "eu@tu",
            "name"  => "Maria Madalena"
        ];

        $response   = $this->json('POST', 'api/v1/users', $templateBody);
        $first_user = $response->original["data"];

        $templateMessage = [
            "status" => "success",
            "data"   => [
                "id"           => 1,
                "name"         => "Maria Madalena",
                "email"        => "eu@tu",
                "cpf"          => "00011122233",
                "phone_number" => null,
                "created_at"   => $first_user->getDateTimeStringFrom('created_at'),
                "updated_at"   => $first_user->getDateTimeStringFrom('updated_at'),
            ]
        ];

        $headers = [
            "x-api-key" => "23SKLJFSL232JLJ232JJJKSKSLJDLAJJASKJLJ32"
        ];

        $res = $this
                    ->withHeaders($headers)
                    ->json('GET', 'api/v1/users-secure/1');

        $res->assertExactJson($templateMessage);
    }

    public function testUserDelete()
    {
        $templateMessage = [
            "status" => "success",
            "data"   => []
        ];

        $response = $this->json('GET', 'api/v1/users-test');

        $response
            ->assertStatus(200)
            ->json($templateMessage);

        $templateBody = [
            "cpf"   => "00011122233",
            "email" => "eu@tu",
            "name"  => "Maria Madalena"
        ];

        $response   = $this->json('POST', 'api/v1/users', $templateBody);
        $first_user = $response->original["data"];

        $this->assertInstanceOf(User::class, $first_user);
        $this->assertEquals(1, $first_user->id);

        $templateBody = [
            "cpf"   => "00011122231",
            "email" => "eu@tus.com",
            "name"  => "Mario Madaleno"
        ];

        $response    = $this->json('POST', 'api/v1/users', $templateBody);
        $second_user = $response->original["data"];

        $this->assertInstanceOf(User::class, $second_user);
        $this->assertEquals(2, $second_user->id);

        $templateMessage = [
            "status" => "success",
            "data"   => [
                $first_user->getFullDetails(),
                $second_user->getFullDetails(),
            ]
        ];

        $response = $this->json('GET', 'api/v1/users-test');

        $response
            ->assertStatus(200)
            ->assertExactJson($templateMessage);

        $response = $this->json('DELETE', 'api/v1/users/1');

        $response->assertStatus(204);

        $response = $this->json('GET', 'api/v1/users-test');

        $templateMessage = [
            "status" => "success",
            "data"   => [
                $second_user->getFullDetails()
            ]
        ];

        $response
            ->assertStatus(200)
            ->assertExactJson($templateMessage);
    }

    public function testUserUpdateNameFail()
    {
        $templateBody = [
            "cpf"   => "00011122233",
            "email" => "eu@tu",
            "name"  => "Maria Madalena"
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response->assertStatus(201);

        $templateBody = [
            "name" => "ajsdflasdjflas lskdjfasjdflasjdf llsdjf lasdjflasjdflas ljssa ldfjasldjflasdjljflasdjflasd ljsfldkajfl"
        ];

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "name" => [
                    "The name may not be greater than 100 characters."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody = [
            "name" => "Ru"
        ];

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "name" => [
                    "The name must be at least 5 characters."
                ]
            ]
        ];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testUserUpdatePhoneFail()
    {
        $templateBody = [
            "cpf"          => "00011122233",
            "email"        => "eu@tu",
            "name"         => "Maria Madalena"
        ];

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "phone_number" => [
                    "The phone number may not be greater than 12 characters."
                ]
            ]
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response->assertStatus(201);

        $templateBody["phone_number"] = "2332322323222";

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);


        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody['phone_number'] = "48";

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage["errors"]["phone_number"] = ["The phone number must be at least 10 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testUserUpdateCPFFail()
    {
        $templateBody = [
            "cpf"          => "00011122233",
            "email"        => "eu@tu",
            "name"         => "Maria Madalena"
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response->assertStatus(201);

        $templateBody["cpf"] = "2332322323222";

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "cpf" => [
                    "The cpf may not be greater than 11 characters."
                ]
            ]
        ];

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody["cpf"] = "48";

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage["errors"]["cpf"] = ["The cpf must be at least 11 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody = ["cpf" => ""];

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage["errors"]["cpf"] = ["The cpf field is required."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }

    public function testUserUpdateEmailFail()
    {
        $templateBody = [
            "cpf"          => "00011122233",
            "email"        => "eu@tu",
            "name"         => "Maria Madalena"
        ];

        $response = $this->json('POST', 'api/v1/users', $templateBody);

        $response->assertStatus(201);

        $templateBody["email"] = "";

        $templateMessage = [
            "status"  => "fail",
            "message" => "The given data was invalid.",
            "errors"  => [
                "email" => [
                    "The email field is required."
                ]
            ]
        ];

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody = ["email" => "48"];

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage["errors"]["email"] = ["The email must be a valid email address."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);

        $templateBody["email"] = "ajsdflasdjflaslskdjfasjdflasjdfllsdjflasdjflasjdflasljssaldfjasldjflasdjljflasdjflasdljsfldkajfl@eus.com.ajsdflasdjflaslskdjfasjdflasjdfllsdjflasdjflasjdflasljssaldfjasldjflasdjljflasdjflasdljsfldkajfl";

        $response = $this->json('PUT', 'api/v1/users/1', $templateBody);

        $templateMessage["errors"]["email"] = ["The email may not be greater than 200 characters."];

        $response
            ->assertStatus(422)
            ->assertExactJson($templateMessage);
    }
}
