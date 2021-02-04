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
    private const KELVIN_DEGREES = 273.15;

    public const CELSIUS_MEASURE = 'Celsius';
    public const KELVIN_MEASURE = 'Kelvin';
    public const FAHRENHEIT_MEASURE = 'Fahrenheit';

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
    public function create(array $weatherInfo, ?string $degreesMeasure = null) : Temperature
    {
        if (isset($weatherInfo['main'])) {
            $temp = new Temperature();

            switch ($degreesMeasure) {
                case 'k' :
                    $temp = $this->getInfoInKelvin($weatherInfo['main'], $temp);
                    break;
                case 'f' :
                    $temp = $this->getInfoInFahrenheit($weatherInfo['main'], $temp);
                    break;
                default:
                    $temp = $this->getInfoInCelsius($weatherInfo['main'], $temp);
                    break;
            }

            $temp->setHumidity($weatherInfo['main']['humidity']);
            $this->entityManager->persist($temp);
            $this->entityManager->flush();

            return $temp;
        }
        throw new \InvalidArgumentException('There is no data available to get the temperature');
    }

    /**
     * Transform temperatures from Fahrenheit to Celsius
     * @param float $degrees
     * @return float
     */
    public function fahrenheitToCelsius(float $degrees) :float
    {
        return (float) number_format(($degrees * 5/9) - 32, 2);
    }

    /**
     * Transform temperatures from Celsius to Fahrenheit
     * @param float $degrees
     * @return float
     */
    public function celsiusToFahrenheit(float $degrees) :float
    {
        return (float) number_format(($degrees * 9/5) + 32, 2);
    }

    /**
     * Transform temperatures from Kelvin to Celsius
     * @param float $degrees
     * @return float
     */
    public function kelvinToCelsius(float $degrees) :float
    {
        return (float) number_format($degrees - self::KELVIN_DEGREES, 2);
    }

    /**
     * Transform temperatures from Celsius to Kelvin
     * @param float $degrees
     * @return float
     */
    public function celsiusToKelvin(float $degrees): float
    {
        return (float) number_format($degrees + self::KELVIN_DEGREES, 2);
    }

    /**
     * Transform temperatures from Celsius to Kelvin
     * @param float $degrees
     * @return float
     */
    public function kelvinToFahrenheit(float $degrees): float
    {
        return number_format(9/5 * ($degrees - self::KELVIN_DEGREES) + 32);
    }

    /**
     * Transform temperatures from Celsius to Kelvin
     * @param float $degrees
     * @return float
     */
    public function fahrenheitToKelvin(float $degrees): float
    {
        return number_format(5/9 * ($degrees - 32) + self::KELVIN_DEGREES);
    }

    private function getInfoInCelsius(array $weather, Temperature $temp): Temperature
    {
        $temp->setRealtime($this->kelvinToCelsius($weather['temp']));
        $temp->setMin($this->kelvinToCelsius($weather['temp_min']));
        $temp->setMax($this->kelvinToCelsius($weather['temp_max']));
        $temp->setFeelsLike($this->kelvinToCelsius($weather['feels_like']));
        $temp->setMeasure(self::CELSIUS_MEASURE);

        return $temp;
    }

    private function getInfoInFahrenheit(array $weather, Temperature $temp): Temperature
    {
        $temp->setRealtime($this->kelvinToFahrenheit($weather['temp']));
        $temp->setMin($this->kelvinToFahrenheit($weather['temp_min']));
        $temp->setMax($this->kelvinToFahrenheit($weather['temp_max']));
        $temp->setFeelsLike($this->kelvinToFahrenheit($weather['feels_like']));
        $temp->setMeasure(self::FAHRENHEIT_MEASURE);

        return $temp;
    }

    private function getInfoInKelvin(array $weather, Temperature $temp): Temperature
    {
        $temp->setRealtime($this->kelvinToFahrenheit($weather['temp']));
        $temp->setMin($this->kelvinToFahrenheit($weather['temp_min']));
        $temp->setMax($this->kelvinToFahrenheit($weather['temp_max']));
        $temp->setFeelsLike($this->kelvinToFahrenheit($weather['feels_like']));
        $temp->setMeasure(self::KELVIN_MEASURE);

        return $temp;
    }


}
