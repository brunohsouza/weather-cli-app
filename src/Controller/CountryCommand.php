<?php

namespace App\Controller;

use App\Entity\Temperature;
use App\Entity\Weather;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use App\Service\CountryService;

class CountryCommand extends Command
{
    /**
     * Receives the CountryService
     * @var CountryService
     */
    private CountryService $countryService;

    /**
     * WeatherController constructor.
     */
    public function __construct(CountryService $countryService) {
        $this->countryService = $countryService;
        parent::__construct();
    }

    /**
     * Method to start and set the console configuration
     */
    public function configure() :void
    {
        $this->setName('country')
            ->setDescription('Application to query for country by abbreviation')
            ->addArgument('list')
            ->addArgument('search')
            ->setHelp('-h');
    }

    /**
     * Execute the actions used in console
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $country = '';
        try {
            $helper = $this->getHelper('question');

            if ($input->getArgument('search')) {
                $countryQuestion = new Question('What is the abbreviation of the country that you want to know about? ');
                $countryAbbreviation = $helper->ask($input, $output, $countryQuestion);
                $country = $this->countryService->getCountryByAcronym($countryAbbreviation);
            }

            if ($input->getArgument('list') || !$input->getArgument()) {
                $country = $this->countryService->getCountryConcurrentRequest();
            }

            if (!is_string($country)) {
//                var_dump($country);
            } else {
                $output->writeln('Country: ' . $country);
            }


            return 0;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
