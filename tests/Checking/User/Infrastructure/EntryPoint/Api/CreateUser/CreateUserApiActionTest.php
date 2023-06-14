<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\CreateUser;

use App\Checking\User\Infrastructure\EntryPoint\Api\CreateUser\CreateUserApiAction;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/users/';

    public function testCreateUserApiActionSuccessfully(): void
    {
        $body = [
            'id' => '15342d22-d819-460a-a662-b679a7fe89b2',
            'name' => 'test',
            'email' => 'test@test.com'
        ];
        $this->client->request('POST', self::uri, $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
