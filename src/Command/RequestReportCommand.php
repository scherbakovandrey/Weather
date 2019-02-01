<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\RequestReport;

class RequestReportCommand extends Command
{
    protected static $defaultName = 'app:request-report';

    /**
     * @var RequestReport
     */
    private $requestReport;

    public function __construct($name = null, RequestReport $requestReport)
    {
        parent::__construct($name);

        $this->requestReport = $requestReport;
    }

    protected function configure()
    {
        $this
            ->setDescription('CLI command to request the reporting data from the data provider')
            ->addArgument('Country', InputArgument::OPTIONAL, 'Country parameter')
            ->addArgument('City', InputArgument::OPTIONAL, 'City parameter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('Country');
        $city = $input->getArgument('City');

        if ($country) {
            $io->note(sprintf('Country: %s', $country));
        }

        if ($city) {
            $io->note(sprintf('City: %s', $city));
        }

        $requestResult = $this->requestReport->process($country, $city);

        if (isset($requestResult['error'])) {
            $io->error($requestResult['error']);
            exit(1);
        }

        $io->success($requestResult['message']);
    }
}
