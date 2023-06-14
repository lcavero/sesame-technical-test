<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseKernelTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }
}
