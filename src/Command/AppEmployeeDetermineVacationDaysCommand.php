<?php

namespace App\Command;

use App\Libs\Employee\Exception\LoggableExceptionInterface;
use App\Libs\EmployeeVacationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppEmployeeDetermineVacationDaysCommand extends Command
{
    protected static $defaultName = 'app:employee:determine-vacation-days';

    /**
     * @var EmployeeVacationService
     */
    protected $employeeVacationService;

    public function __construct(EmployeeVacationService $employeeVacationService)
    {
        $this->employeeVacationService = $employeeVacationService;

        parent::__construct(null);
    }

    /**
     * @return EmployeeVacationService
     */
    public function getEmployeeVacationService(): EmployeeVacationService
    {
        return $this->employeeVacationService;
    }

    protected function configure()
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'Please set an year')
            ->setDescription('app:employee:determine-vacation-days')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //@todo prevent double command run

        $io = new SymfonyStyle($input, $output);

        $year = (int) $input->getArgument('year');

        try {
            $this->employeeVacationService->determineEmployeeVacationDays($year);

            //@todo get result from service and output in debug mode
            //@todo log results of the command

        } catch (LoggableExceptionInterface $e) {
            $io->error($e->getLogMessage());
            //log custom exception implemented with LoggableExceptionInterface here $e->getLogMessage()
        }

        $io->success('Show result here...');
    }
}
