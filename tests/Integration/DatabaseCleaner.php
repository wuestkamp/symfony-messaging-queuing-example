<?php declare(strict_types=1);

namespace App\Tests\Integration;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * inspired by https://www.sitepoint.com/quick-tip-testing-symfony-apps-with-a-disposable-database/
 */
class DatabaseCleaner
{
    /**
     * @param KernelInterface $kernel
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public static function prime(KernelInterface $kernel)
    {
        // Make sure we are in the test environment
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }

        // Get the entity manager from the service container
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        // Run the schema update tool using our entity metadata
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($metadatas);
        $schemaTool->createSchema($metadatas);

        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $query = 'drop table if exists messenger_messages';
        $statement = $em->getConnection()->prepare($query);
        $statement->execute();
        $statement->fetchAll();
    }
}
