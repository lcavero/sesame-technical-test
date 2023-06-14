<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\ListUsers;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ListUsersApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/users/';

    public function testCreateUserApiActionSuccessfully(): void
    {
        $this->client->request('GET', self::uri);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
