<?php

namespace App\Command;

use App\Service\Parser\ParserInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'parse:news',
    description: 'Команда для парсинга новостей',
)]
class ParseNewsCommand extends Command
{

    public function __construct(private readonly ParserInterface $parser)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('count', InputArgument::OPTIONAL, 'Количество новостей', 15);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Start parsing...');
        $count = $input->getArgument('count');
        $newsList = $this->parser->parse($count);
        $io->success('Success!');

        return Command::SUCCESS;
    }
}
