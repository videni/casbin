<?php

declare(strict_types=1);

namespace Videni\Casbin\Bridge\Symfony\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Videni\Casbin\Adapter\DatabaseAdapter;
use Videni\Casbin\AdapterRegistry;

final class InstallTableCommand extends Command
{
    private array $adapters;

    public function __construct(AdapterRegistry $adapterRegistry)
    {
        $this->adapters = $this->getInstallableAdapters($adapterRegistry);

        parent::__construct();
    }

    protected function configure(): void
    {
        $installables =  count(array_keys($this->adapters)) > 0 ? sprintf('<info>%s</info>', join(',', array_keys($this->adapters))): 'None';

        $this
            ->setName('videni-casbin:install')
            ->setDescription('Installs Casbin database tables.')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> command installs Casbin database tables for your Casbin enforcer, 
only necessary if you load Casbin policies from database. installable adapters: {$installables} 
EOT
            )
            ->addArgument('adapter', InputArgument::REQUIRED, 'A database adapter you configured')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $adapters = $this->adapters;

        $adapter = $input->getArgument('adapter');
        if (!in_array($adapter, array_keys($adapters))) {
            $output->writeln(sprintf('<error>Adapter %s not configured, only %s available</error>', $adapter, join(',', array_keys($adapters))));

            return 1;
        }

        /** @var \Videni\Casbin\Adapter\DatabaseAdapter  $adapter */
        $adapter = $adapters[$adapter];
        $adapter->initTable();

        $output->writeln('<info>Done</info>');

        return 0;
    }

    private function getInstallableAdapters(AdapterRegistry $adapterRegistry): array
    {
        $adapters  = $adapterRegistry->all();

        return array_filter($adapters, function($adapter) {
            return $adapter instanceof DatabaseAdapter;
        }, ARRAY_FILTER_USE_BOTH);
    }
}