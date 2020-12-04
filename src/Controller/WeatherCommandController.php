<?php

namespace App\Controller;

use App\Entity\Temperature;
use App\Entity\Weather;
use App\Service\CityService;
use App\Service\TemperatureService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use App\Service\WeatherService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class WeatherCommandController
 * This class is used on request made by CLI
 * @package Weather\Controller
 */
class WeatherCommandController extends Command
{
    /**
     * Receives the weatherService
     * @var WeatherService
     */
    private WeatherService $weatherService;

    /**
     * WeatherController constructor.
     */
    public function __construct(WeatherService $weatherService) {
        $this->weatherService = $weatherService;
        parent::__construct();
    }

    /**
     * Method to start and set the console configuration
     */
    public function configure() :void
    {
        $this->setName('weather')
            ->setDescription('Application to query the weather on a local city')
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
        try {
            $helper = $this->getHelper('question');

            $cityNameQuestion = new Question('Which city do you want to know the weather? ');
            $cityName = $helper->ask($input, $output, $cityNameQuestion);

            $weather = $this->weatherService->getWeatherByCity($cityName);
            $temperature = $weather->getTemperature();

            $this->showsOutput($weather);

            if ($weather instanceof Weather && $temperature instanceof Temperature) {
                $output->writeln(
                    ucfirst($weather->getDescription()) . ', ' .
                    ' Temperature: ' . $weather->getTemperature()->getRealtime() . ' degrees Celsius'
                );

                $output->writeln('Minimum Temperature: ' . $weather->getTemperature()->getMin() . ' degrees Celsius');
                $output->writeln('Maximum Temperature: ' . $weather->getTemperature()->getMax() . ' degrees Celsius');
                $output->writeln('Perceived Temperature: ' . $weather->getTemperature()->getFeelsLike() . ' degrees Celsius');
                $output->writeln('Relative Humidity: ' . $weather->getTemperature()->getHumidity() . '%');
                $output->writeln('Wind Speed: ' . $weather->getWindSpeed() . ' Km/h');

                return 0;
            }

            $output->writeln('There was a problem with this request.');
            return 1;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Method that formats the data to show in console
     * @param $weatherData
     */
    public function showsOutput($weatherData): void
    {
    }
}
