<?php

namespace App\Service;

use App\Entity\Temperature;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TemperatureService
 * @package Weather\Service
 */
class TemperatureService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Receives the city data object and pass to the function to become the fahrenheit degrees to celsius
     * @param $weatherInfo
     * @return Temperature
     */
    public function create(array $weatherInfo) : Temperature
    {
        if (isset($weatherInfo['main'])) {
            $temp = new Temperature();
            $temp->setRealtime($this->kelvinToCelsius($weatherInfo['main']['temp']));
            $temp->setMin($this->kelvinToCelsius($weatherInfo['main']['temp_min']));
            $temp->setMax($this->kelvinToCelsius($weatherInfo['main']['temp_max']));
            $temp->setFeelsLike($this->kelvinToCelsius($weatherInfo['main']['feels_like']));
            $temp->setHumidity($weatherInfo['main']['humidity']);
            $this->entityManager->persist($temp);
            $this->entityManager->flush();

            return $temp;
        }
        throw new \InvalidArgumentException('There is no data available to get the temperature');
    }

    /**
     * Transform temperatures from Fahrenheit to Celsius
     * @param float $temp
     * @return float
     */
    public function fahrenheitToCelsius(float $temp) :float
    {
        return (float) number_format(($temp - 32) / 1.8000, 2);
    }

    /**
     * Transform temperatures from Kelvin to Celsius
     * @param float $temp
     * @return float
     */
    public function kelvinToCelsius(float $temp) :float
    {
        return (float) number_format($temp - 273.15, 2);
    }
}
