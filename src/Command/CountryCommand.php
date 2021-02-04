<?php

namespace App\Command;

use App\Service\CountryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class CountryCommand extends Command
{
    private const OPTION_SEARCH = 's';
    private const OPTION_LIST = 'l';
    private const OPERATIONS = ['s' => 'search', 'l' => 'list'];


    protected static $defaultName = 'app:country';
    /**
     * @var CountryService
     */
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        parent::__construct(self::$defaultName);

        $this->countryService = $countryService;
    }

    protected function configure()
    {
        $this->setName('country')
            ->setDescription('Command to query for country by name, abbreviation or region')
            ->setHelp('-h');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countryQuestion = new ChoiceQuestion(
            'Which operation do you want to execute now? ',
            self::OPERATIONS
        );

        $helper = new QuestionHelper();
        $operation = $helper->ask($input, $output, $countryQuestion);

        try {
            if (self::OPTION_SEARCH === $operation) {
                $acronymsQuestion = new Question(
                    'What country do you want to know about? You can search by region, name or abbreviation' . PHP_EOL
                );

                $countryAcronyms = $helper->ask($input, $output, $acronymsQuestion);

                $io->info(sprintf('You had request the %s operation for the country %s',
                    self::OPERATIONS[self::OPTION_SEARCH],
                    $countryAcronyms)
                );

                $country = $this->countryService->getCountryByUserInfo($countryAcronyms);
                $io->success(sprintf('The result of this operation is: %s', $country));
            }

            if (self::OPTION_LIST === $operation) {
                $io->info(sprintf('You had request the %s operation ', self::OPERATIONS[self::OPTION_LIST]));
                $countryList = $this->countryService->getCountryList();
                $io->success(sprintf('The result of this operation is: %s', $countryList));
            }
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
