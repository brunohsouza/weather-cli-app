<?php

namespace Test;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use App\Service\WeatherService;

/**
 * @coversDefaultClass \App\Service\WeatherService
 */
class WeatherServiceTest extends KernelTestCase
{
    /**
     * @var WeatherService
     */
    private WeatherService $weatherService;

    public function setUp(): void
    {
        static::bootKernel();
        $this->weatherService = self::$container->get(WeatherService::class);
        parent::setUp();
    }

    /**
     * Tests the getWeatherByCity function with city = London
     * @covers ::getWeatherByCity
     * @depends testGetWeatherFromApi
     * @group weather-city1
     */
    public function testGetWeatherByCity() :void
    {
        $cityName = 'London';
        $result = $this->weatherService->getWeatherByCity($cityName);

        $this->assertNotEmpty($result);
        $this->assertIsFloat($result->temp->realtime);
        $this->assertIsFloat($result->temp->min);
        $this->assertIsFloat($result->temp->max);
        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertTrue(isset($result->overall));
        $this->assertIsString($result->overall->desc);
        $this->assertIsInt($result->overall->humidity);
    }

    /**
     * Tests the getWeatherByCity function with cityName empty
     */
    public function testGetWeatherByCityWithoutParams() :void
    {

        $params = new \stdClass();
        $params->query = '';
        $this->weatherService->getWeatherByCity($params);
    }


    /**
     * Tests the function weatherCityData
     * @group weather-city
     * @throws \Exception
     */
    public function testWeatherCityData() :void
    {
        $result = $this->weatherService->getCityData('Berlin');

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('country', $result);
        $this->assertArrayHasKey('coordinates', $result);
    }

    /**
     * Tests the function weatherCityData without params
     * @group weather-city
     */
    public function testWeatherCityDataWithoutParams() :void
    {
        $this->expectException(\Exception::class);
        $this->weatherService->getCityData('');
    }

    /**
     * @dataProvider httpResponseProvider
     */
    public function testGetWeatherFromApi(array $response) :void
    {
        $client = new MockHttpClient([$response]);
        $response = $client->request(
            'GET',
            'http://api.openweathermap.org/data/2.5/weather',
            [
                'query' => [
                    'name' => 'Dublin',
                    'appid' => bin2hex('app-id')
                ],
                'timeout' => 5.0
            ]
        );

        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('name', $content);
        $this->assertEquals('Dublin', $content['name']);
        $this->assertArrayHasKey('weather', $content);
        $this->assertArrayHasKey('main', $content);
        $this->assertArrayHasKey('temp', $content['main']);
        $this->assertEquals(1, $client->getRequestsCount());
    }

    public function httpResponseProvider(): \Generator
    {
        yield 'response-success' => [
            (function() {
                $body = file_get_contents('data/weather-response.json');
                $info = (array) file_get_contents('data/weather-response-info.txt');

                return new MockResponse($body, $info);
            })()
        ];
        yield 'response-failed' => [
            (function() {
                $body = file_get_contents('data/weather-response-failed.json');
                $info = (array) file_get_contents('data/weather-response-failed-info.txt');

                return new MockResponse($body, $info);
            })()
        ];
    }

    /**
     * TemperatureServiceTest the response inside the api request to check the overall parameters
     * @group overall
     */
    public function testGetOverall() :void
    {
        $arrCityData = $this->weatherService->getCityData('Brasilia');
        $weatherData = $this->weatherService->getWeatherFromApi($arrCityData['id']);
        $result = $this->weatherService->getMeaningInformation($weatherData);
        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertTrue(isset($result->desc));
        $this->assertTrue(isset($result->humidity));
        $this->assertIsString($result->desc);
        $this->assertIsInt($result->humidity);
    }
}
