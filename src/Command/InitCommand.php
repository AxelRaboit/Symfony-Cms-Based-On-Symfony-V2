<?php

namespace App\Command;

use App\Enum\DataEnum;
use App\Manager\DataEnumManager;
use App\Repository\DataEnumRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitCommand extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly DataEnumRepository $dataEnumRepository,
        private readonly DataEnumManager $dataEnumManager,
    ) {
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
            ->setName('app:init')
            ->setDescription('Init app.')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        error_reporting(0);

        $io = new SymfonyStyle($input, $output);

        $this->logger->info(static::class.' : '.debug_backtrace()[1]['function']); // Run log

        $io->title('Start - Init app...');

        try {
            // Getting constants - Data
            $io->section('Datas...');
            $io->section('Datas - APP');
            $constants = DataEnum::getConstants();
            foreach ($constants as $key => $value) {
                $constant = $this->dataEnumRepository->findOneBy(['devKey' => $value]);
                if (null === $constant) {
                    $io->note($key.' - CREATED');
                    $currentData = DataEnum::$data[$value];
                    $this->dataEnumManager->createFromArray($currentData);
                }
            }

            $io->success('Successfully finished.');

            return 0;
        } catch (\Exception $e) {
            $this->writeError($io, sprintf('An error has been occured : %s.', $e->getMessage()), $e);

            return 1;
        }
    }

    // BASE

    private function writeError(
        SymfonyStyle $io,
        string $message,
        \Exception $e = null
    ): void {
        $io->error($message);
        if (null !== $e) {
            $this->logger->error($message, ['context' => $e]);
        } else {
            $this->logger->error($message);
        }
    }
}
