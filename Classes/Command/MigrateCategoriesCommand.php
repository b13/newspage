<?php
declare(strict_types=1);

namespace B13\Newspage\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MigrateCategoriesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDescription('Migrate category to categories.')
            ->setHelp('This command updates news pages by copying the value of the old category field to the new categories field.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Migrating News Categories');

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');

        $affectedRows = $queryBuilder
            ->update('pages')
            ->set('tx_newspage_categories', $queryBuilder->quoteIdentifier('tx_newspage_category'), false)
            ->where(
                $queryBuilder->expr()->eq('doktype', $queryBuilder->createNamedParameter(24, \PDO::PARAM_INT)),
                $queryBuilder->expr()->neq('tx_newspage_category', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
            )
            ->execute();

        $io->success('Categories for ' . $affectedRows . ' news successfully migrated.');
    }
}
