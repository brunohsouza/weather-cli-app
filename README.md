# weather-cli-app
Simple application to query the weather by city name

> This is an under construction and experimental application

The structure of this application based as the following map:

```
  weather-app
  |--.docker
  |    -- apache
  |    -- php
  |    -- docker-compose.yml
  |--data
  |--src
  |    -- Config
  |    -- Controller
  |    -- Core
  |    -- Service
  |-- tests
  |  README.md
  |  composer.json
  |  console.php
  |  index.php
  |  phpunit.xml
```  

The .docker folder has two directories. One for apache container and one for php container. The both has the own 
Dockerfile. The docker-compose.yml is the responsible for build, start or stop the container.

**USAGE (CLI)**

To start the containers, just type the command below on terminal inside the .docker folder:

```
docker-compose up
```

This application has two services mode. One mode is for command line (CLI) and the other is for API.

To use this application as command line service, type the command in terminal on the applications's root folder, as following:

```
php console.php weather
```

This command will print the following question on the terminal:

```
Type a name of a city that you want to know the weather:
```

Type the name of the city that you are interested to know about the weather as follow:

```
Type a name of a city that you want to know the weather: Berlin
```

The application will prints the answer with the weather and relevant information like:

```
Clear,  Temperature in 4.32 degrees Celsius
Minimum Temperature Forecast: 4 degrees Celsius
Maximum Temperature Forecast: 5 degrees Celsius
Relative Humidity: 58%
``` 

**DEPENDENCIES**

*Symfony console is a friendly, easier and elegant way to work with console mode on php applications.

*Symfony HttpClient is an excellent tool to work with request to API's. It has a secure response treatment and friendly requests.

 
**SUPPORT AND SOURCE CODE**

**Data**

The data folder has the file city.list.json. This file is a big JSON file with the some data from a lot of cities
around the world. This data file has the id, name, country and coordinates from the city, as below:

```
  {
    "id": 707860,
    "name": "Hurzuf",
    "country": "UA",
    "coord": {
      "lon": 34.283333,
      "lat": 44.549999
    }
  }
```

**NOTE**: On the Open Weather Map site, they recommends to use their API with a query request that has the city ID 
instead of use the name in string format. So the requests from this application to Open Weather Map API is made by
city ID as recommended.  

**Config**

The Config directory has the routes.json file. This file is a route register that is used by the Router file to 
designate the route to follow.

**Controller**

The Controller Directory has two classes. The WeatherCommandController is used by the console mode. 
The WeatherController is used by the API mode. The both has the same responsibility basically that is receive the data
from input request, to pass this data to the Service layer and send the response data to output.

**Core**

The Core layer has the files responsible to manage the requests to thi application when made by a browser or API mode.

**Service**

The Service layer has the classes based on the business rules of the application. The TemperatureService is a class that is 
responsible to treat the temperature data and makes some conversion like Kelvin to Celsius or Fahrenheit to Celsius.
The WeatherService has the responsible to get the weather data by city and makes the requisition to Open Weather Map Api.

**tests**

This directory has the unit tests based on the service classes. To run the tests execute this command on the 
application's root folder in a terminal:

```
./vendor/bin/phpunit
```