<?php declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseKernelTestCase extends WebTestCase
{
    protected EntityManagerInterface $em;
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->em = $this->getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->setAutoCommit(false);
    }
}
