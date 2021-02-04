<?php

namespace App\Command;

use App\Entity\Temperature;
use App\Entity\Weather;
use App\Service\TemperatureService;
use App\Service\WeatherService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class WeatherCommand extends Command
{
    protected static $defaultName = 'app:weather';

    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct(self::$defaultName);
        $this->weatherService = $weatherService;
    }

    protected function configure()
    {
        $this->setName('weather')
            ->setDescription('Command to query the weather on a local city')
            ->setHelp('-h');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cityNameQuestion = new Question('Which city do you want to know the weather? ');
        $cityName = $io->askQuestion($cityNameQuestion);

        $io->comment('The temperature is showing in degrees Celsius by default.');

        $temperatureQuestion = new ConfirmationQuestion('Do you want to keep the temperature in degrees Celsius?');
        $isCelsius = $io->askQuestion($temperatureQuestion);

        if (!$isCelsius) {
            $degreesQuestion = new ChoiceQuestion(
                'Do you want to use the temperature in which measure?',
                [
                    'k' => TemperatureService::KELVIN_MEASURE,
                    'f' => TemperatureService::FAHRENHEIT_MEASURE,
                    'c' => TemperatureService::CELSIUS_MEASURE
                ],
                'c'
            );
            $degreesMeasure = $io->askQuestion($degreesQuestion);
            $io->info(sprintf('You had chose to use the temperature measure in %s', $degreesMeasure));
        }

        $weather = $this->weatherService->getWeatherByCity($cityName, $degreesMeasure);
        $temperature = $weather->getTemperature();

        if ($weather instanceof Weather && $temperature instanceof Temperature) {
            $io->success('The search returned with a success');

            $io->writeln(
                ucfirst($weather->getDescription()) . ', ' .
                ' Temperature: ' . $temperature->getRealtime() . ' degrees ' . $temperature->getMeasure()
            );

            $io->writeln('Minimum Temperature: ' .
                $temperature->getMin() . ' degrees ' . $temperature->getMeasure()
            );

            $io->writeln('Maximum Temperature: ' .
                $temperature->getMax() . ' degrees ' . $temperature->getMeasure()
            );

            $io->writeln('Perceived Temperature: ' .
                $temperature->getFeelsLike() . ' degrees ' . $temperature->getMeasure()
            );

            $io->writeln('Relative Humidity: ' . $temperature->getHumidity() . '%');

            $io->writeln('Wind Speed: ' . $weather->getWindSpeed() . ' Km/');

            return Command::SUCCESS;
        }

        $io->writeln('There was a problem with this request.');
        return Command::FAILURE;
    }
}
