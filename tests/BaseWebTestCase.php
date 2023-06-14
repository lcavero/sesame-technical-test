<?php declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseWebTestCase extends WebTestCase
{
    protected EntityManagerInterface $em;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->em = $this->getContainer()->get(EntityManagerInterface::class);
        $this->em->getConnection()->setAutoCommit(false);
    }

    protected function getResponse(): ?array
    {
        $responseData = $this->client->getResponse();
        return json_decode($responseData->getContent(), true);
    }
}
