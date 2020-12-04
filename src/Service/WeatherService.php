<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Weather;
use Doctrine\ORM\EntityManagerInterface;
use JsonMachine\JsonMachine;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class WeatherService
 * @package Weather\Service
 */
class WeatherService
{

    /**
     * Service to get and treat the temperature as Celsius
     */
    private TemperatureService $tempService;

    /**
     * Http Client to make the request to weather API
     */
    private HttpClientInterface $client;

    private CityService $cityService;

    private string $openweathermap_id;

    private EntityManagerInterface $entityManager;

    /**
     * WeatherService constructor.
     */
    public function __construct(
        HttpClientInterface $openweather,
        TemperatureService $temperatureService,
        CityService $cityService,
        LoggerInterface $logger,
        string $openweathermap_id,
        EntityManagerInterface $entityManager
    ) {
        $this->client = $openweather;
        $this->tempService = $temperatureService;
        $this->cityService = $cityService;
        $this->logger = $logger;
        $this->openweathermap_id = $openweathermap_id;
        $this->entityManager = $entityManager;
    }

    private function create(array $weatherData, City $city): Weather
    {
        $temp = $this->tempService->create($weatherData);

        $weather = $this->entityManager->getRepository(Weather::class)->findOneBy(['city' => $city]);
        if (empty($weather)) {
            $weather = new Weather();
            $weather->setMain($weatherData['weather'][0]['main']);
            $weather->setCity($city);
            $weather->setDescription($weatherData['weather'][0]['description']);
            $weather->setWindSpeed($weatherData['wind']['speed']);
            $weather->setTemperature($temp);
            $this->entityManager->persist($weather);
            $this->entityManager->flush();
        }

        return $weather;
    }

    /**
     * Method to manage and return weather data
     * @param $params
     * @return \stdClass
     * @throws \Exception
     */
    public function getWeatherByCity(string $cityName): Weather
    {
        $city = $this->getCityData($cityName);


        return $this->create($this->getWeatherFromApi($city->getId()), $city);
    }

    /**
     * @param $cityName
     * @return array|mixed
     * @throws \Exception
     */
    public function getCityData(string $cityName): City
    {
        $city = $this->cityService->findByName(urldecode($cityName));

        if (!$city) {
            throw new \InvalidArgumentException('Invalid city name sent to get data');
        }

        return $city;
    }

    /**
     * @description  Method to make the request to OpenWeatherApi
     * @param int $cityId
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface|DecodingExceptionInterface
     */
    public function getWeatherFromApi(int $cityId)
    {
        try {
            $response = $this->client->request(
                'GET',
                '/data/2.5/weather',
                [
                    'query' => [
                        'id' => $cityId,
                        'appid' => $this->openweathermap_id
                    ],
                    'timeout' => 5.0,
                    'extra' => [
                        'curl' => [
                            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V6
                        ]
                    ]
                ]
            );

            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
            $this->logger->error($e->getMessage(), $e->getResponse()->toArray());
        } catch (RedirectionExceptionInterface $e) {
            $this->logger->warning($e->getMessage(), $e->getResponse()->toArray());
        } catch (ServerExceptionInterface $e) {
            $this->logger->error($e->getMessage(), [
                'status' => $e->getResponse()->getStatusCode(),
                'content' => $e->getResponse()->toArray(),
                'info' => $e->getResponse()->getInfo()
            ]);
        } catch (TransportExceptionInterface $e) {
            // we do not have response in this case as should be a network issue
            $this->logger->error($e->getMessage());
        }

    }

    /**
     * Method to get the overall information like weather description and humidity
     * @param $weatherData
     * @return \stdClass
     */
    public function getMeaningInformation($weatherData) :\stdClass
    {
        $weather = new \stdClass();
        foreach ($weatherData->weather as $key => $desc) {
            $weather->desc[$key] = $desc->main;
        }
        $weather->desc = implode(', ', $weather->desc);
        $weather->humidity = $weatherData->main->humidity;
        return $weather;
    }
}
