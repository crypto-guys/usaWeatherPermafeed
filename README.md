# usaWeatherFeed


### What does this do?
This bot will retrieve current weather and weather forecast data then save that data to Arweave.
This bot uses a list of the top 1000 US cities by population and the openweatherMap API to get weather data.
Current weather is retrieved every 2 hours. 
Forecast data is retrieved 1 time per day.


### How was the API service chosen?
I evaluated 5 different weather api services for potential usefulness. 
Based on the free plan limits and the data returned it seemed like openweathermap API was the best choice.
I see no reason to include a second API source at this time. 
All data sources offer mostly the same data and openweathermap is generous with their free plan.

### Why not archive data for the whole world?
There are over 100000 cities in the world. The API provider only allows 1 API request per second. I think there are only 86400 seconds in 1 day. Plus it is a lot of data.

## Requirements
1 Linux Host

git

The following php packages if you use ubuntu the equivilant if not.
- php,php-common,php-curl,php-gmp,php-mbstring,php-mysql,php-pear,php-php-gettext,php-phpseclib,php-xml,php7.2,php7.2-cli,php7.2-common,php7.2-curl,php7.2-gmp,php7.2-json,php7.2-mbstring,php7.2-mysql,php7.2-opcache,php7.2-readline,php7.2-xml,php7.2-zip

## Install
**change to the root directory of your host**
- cd /

**clone this repository**
- git clone https://github.com/crypto-guys/usaWeatherFeed.git

**edit crontab**
- crontab -e

**add the following lines**
- 0 */2 * * * php -f /usaWeatherFeed/openweathermapFunctions.php >> /usaWeatherFeed/current.log
- 0 5 * * * php -f /usaWeatherFeed/owmCityForecasts.php >> /usaWeatherFeed/forecast.log
- save crontab

**Thats all the bot is installed and scheduled**

# Use

### Tags:
**Date-Type:**
- value = Current or Forecast 
- use = used to specify either Current or Forecast Data

**City:**
- value = name of the City (case sensitive)
- example = Dallas

**State:**
- value = One of the 50 States (case sensitive)
- example = Texas

**Date/Time:**    

All time is Coordinated Universal Time (UTC) 

Forecast Data
- value = Month-Day-Year
- only the Date is used / needed
- Forecast data is retrieved daily
- example = 12-27-2019

Current Data
- value = Month-Day-Year Hour:00
- use = search for data by time
- current data is retrieved every 2 hours
- This means you should only use even numbered hours for queries
- The date time format is Month-Day-Year Hour:00
- example = 12-27-19 2:00

# ArQL Example
**These are examples only and do not contain all possible options. Examples should give the user enough information to create queries for the required data**
**ALL WEATHER DATA IS JSON FORMAT**

##### Retrieve All Data ever saved by the USAWeatherFeed
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     }
    }

##### Retrieve all Forecast Data
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Forecast'
     }
    }

##### Retrieve all Current Data 
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Current'
     }
    }


##### Retrieve All Data (Current and Forecast) for New York, New York
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'City:',
        expr2: 'New York'
     },
      expr4: {
        op: 'equals',
        expr1: 'State:',
        expr2: 'New York'
     }
    }

##### Retrieve All Current weather for New York, New York
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'City:',
        expr2: 'New York'
     },
      expr4: {
        op: 'equals',
        expr1: 'State:',
        expr2: 'New York' 
     },
      expr5: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Current'
     }
    }
    
##### Retrieve all locations that have reported Current clear weather
##### Weather Types = Clear, Rain, Fog, Mist, Snow, Extreme   list may be incomplete i was unable to locate this is the documentation working to document better    
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'Weather:',
        expr2: 'Clear'
     },
      expr4: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Current'
     }
    }

##### Retrieve all Forecast data for New York, New York       

    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Forecast'
     },
      expr4: {
        op: 'equals',
        expr1: 'City:',
        expr2: 'New York'
     },
      expr5: {
        op: 'equals',
        expr1: 'State:',
        expr2: 'New York'
     }
    }

##### Retrieves Forecast data for New York, New York where the data was obtained on 12-27-2019
 
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Forecast'
     },
      expr4: {
        op: 'equals',
        expr1: 'City:',
        expr2: 'New York'
     },
      expr5: {
        op: 'equals',
        expr1: 'State:',
        expr2: 'New York'
     },
      expr6: {
        op: 'equals',
        expr1: 'Date:',
        expr2: '12-27-2019'
     }
    }

##### Retrieves Current for New York, New York where the data was obtained on 12-27-2019 at 12:00pm
  
    {
      op: 'and',
      expr1: {
        op: 'equals',
        expr1: 'from',
        expr2: 'kgMcQtTK4ZrNNg06eUltX60NRsUK8S_VTffoxU2Mn5Y'
     },
      expr2: {
        op: 'equals',
        expr1: 'Feed-Name:',
        expr2: 'usaWeatherFeed'
     },
      expr3: {
        op: 'equals',
        expr1: 'Data-Type:',
        expr2: 'Forecast'
     },
      expr4: {
        op: 'equals',
        expr1: 'City:',
        expr2: 'New York'
     },
      expr5: {
        op: 'equals',
        expr1: 'State:',
        expr2: 'New York'
     },
      expr6: {
        op: 'equals',
        expr1: 'Date:',
        expr2: '12-27-2019 12:00'
     }
    }

## Example Return Data for Current
    
    {"coord":{"lon":-118.24,"lat":34.05},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"base":"stations","main":{"temp":280.6,"feels_like":278.21,"temp_min":275.37,"temp_max":285.15,"pressure":1017,"humidity":57},"visibility":16093,"wind":{"speed":0.47,"deg":35},"clouds":{"all":1},"dt":1577520334,"sys":{"type":1,"id":3694,"country":"US","sunrise":1577545045,"sunset":1577580677},"timezone":-28800,"id":5368361,"name":"Los Angeles","cod":200}

## Example Return Data for Forecast
    {"cod":"200","message":0,"cnt":40,"list":[{"dt":1577523600,"main":{"temp":278.28,"feels_like":274.2,"temp_min":278.28,"temp_max":280.7,"pressure":1021,"sea_level":1021,"grnd_level":1021,"humidity":89,"temp_kf":-2.42},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":95},"wind":{"speed":3.81,"deg":307},"sys":{"pod":"n"},"dt_txt":"2019-12-28 09:00:00"},{"dt":1577534400,"main":{"temp":278.11,"feels_like":271.84,"temp_min":278.11,"temp_max":279.93,"pressure":1023,"sea_level":1023,"grnd_level":1023,"humidity":80,"temp_kf":-1.82},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03n"}],"clouds":{"all":47},"wind":{"speed":6.52,"deg":316},"sys":{"pod":"n"},"dt_txt":"2019-12-28 12:00:00"},{"dt":1577545200,"main":{"temp":278.09,"feels_like":272.63,"temp_min":278.09,"temp_max":279.3,"pressure":1024,"sea_level":1024,"grnd_level":1024,"humidity":74,"temp_kf":-1.21},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04d"}],"clouds":{"all":61},"wind":{"speed":5.12,"deg":322},"sys":{"pod":"d"},"dt_txt":"2019-12-28 15:00:00"},{"dt":1577556000,"main":{"temp":279.66,"feels_like":275.24,"temp_min":279.66,"temp_max":280.27,"pressure":1024,"sea_level":1024,"grnd_level":1024,"humidity":67,"temp_kf":-0.61},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04d"}],"clouds":{"all":80},"wind":{"speed":3.65,"deg":304},"sys":{"pod":"d"},"dt_txt":"2019-12-28 18:00:00"},{"dt":1577566800,"main":{"temp":281.15,"feels_like":277.19,"temp_min":281.15,"temp_max":281.15,"pressure":1024,"sea_level":1024,"grnd_level":1023,"humidity":68,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04d"}],"clouds":{"all":100},"wind":{"speed":3.37,"deg":291},"sys":{"pod":"d"},"dt_txt":"2019-12-28 21:00:00"},{"dt":1577577600,"main":{"temp":280.55,"feels_like":276.81,"temp_min":280.55,"temp_max":280.55,"pressure":1025,"sea_level":1025,"grnd_level":1024,"humidity":70,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":100},"wind":{"speed":3.02,"deg":335},"sys":{"pod":"n"},"dt_txt":"2019-12-29 00:00:00"},{"dt":1577588400,"main":{"temp":279.86,"feels_like":275.77,"temp_min":279.86,"temp_max":279.86,"pressure":1024,"sea_level":1024,"grnd_level":1024,"humidity":70,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":100},"wind":{"speed":3.36,"deg":291},"sys":{"pod":"n"},"dt_txt":"2019-12-29 03:00:00"},{"dt":1577599200,"main":{"temp":279.41,"feels_like":274.42,"temp_min":279.41,"temp_max":279.41,"pressure":1024,"sea_level":1024,"grnd_level":1023,"humidity":67,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":100},"wind":{"speed":4.42,"deg":313},"sys":{"pod":"n"},"dt_txt":"2019-12-29 06:00:00"},{"dt":1577610000,"main":{"temp":279.23,"feels_like":274.83,"temp_min":279.23,"temp_max":279.23,"pressure":1024,"sea_level":1024,"grnd_level":1023,"humidity":66,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":100},"wind":{"speed":3.5,"deg":281},"sys":{"pod":"n"},"dt_txt":"2019-12-29 09:00:00"},{"dt":1577620800,"main":{"temp":279,"feels_like":275.35,"temp_min":279,"temp_max":279,"pressure":1024,"sea_level":1024,"grnd_level":1024,"humidity":64,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":100},"wind":{"speed":2.29,"deg":307},"sys":{"pod":"n"},"dt_txt":"2019-12-29 12:00:00"},{"dt":1577631600,"main":{"temp":279.18,"feels_like":275.07,"temp_min":279.18,"temp_max":279.18,"pressure":1024,"sea_level":1024,"grnd_level":1024,"humidity":65,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04d"}],"clouds":{"all":90},"wind":{"speed":3.02,"deg":57},"sys":{"pod":"d"},"dt_txt":"2019-12-29 15:00:00"},{"dt":1577642400,"main":{"temp":279.4,"feels_like":275.47,"temp_min":279.4,"temp_max":279.4,"pressure":1022,"sea_level":1022,"grnd_level":1022,"humidity":66,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04d"}],"clouds":{"all":95},"wind":{"speed":2.85,"deg":114},"sys":{"pod":"d"},"dt_txt":"2019-12-29 18:00:00"},{"dt":1577653200,"main":{"temp":280.01,"feels_like":275.67,"temp_min":280.01,"temp_max":280.01,"pressure":1020,"sea_level":1020,"grnd_level":1020,"humidity":74,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10d"}],"clouds":{"all":100},"wind":{"speed":3.94,"deg":134},"rain":{"3h":0.06},"sys":{"pod":"d"},"dt_txt":"2019-12-29 21:00:00"},{"dt":1577664000,"main":{"temp":279.85,"feels_like":275.18,"temp_min":279.85,"temp_max":279.85,"pressure":1020,"sea_level":1020,"grnd_level":1019,"humidity":89,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":100},"wind":{"speed":5.07,"deg":123},"rain":{"3h":3},"sys":{"pod":"n"},"dt_txt":"2019-12-30 00:00:00"},{"dt":1577674800,"main":{"temp":280.5,"feels_like":274.53,"temp_min":280.5,"temp_max":280.5,"pressure":1018,"sea_level":1018,"grnd_level":1017,"humidity":94,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10n"}],"clouds":{"all":100},"wind":{"speed":7.35,"deg":115},"rain":{"3h":5.69},"sys":{"pod":"n"},"dt_txt":"2019-12-30 03:00:00"},{"dt":1577685600,"main":{"temp":281.32,"feels_like":275.54,"temp_min":281.32,"temp_max":281.32,"pressure":1014,"sea_level":1014,"grnd_level":1014,"humidity":97,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10n"}],"clouds":{"all":100},"wind":{"speed":7.5,"deg":106},"rain":{"3h":5.19},"sys":{"pod":"n"},"dt_txt":"2019-12-30 06:00:00"},{"dt":1577696400,"main":{"temp":282.04,"feels_like":277.09,"temp_min":282.04,"temp_max":282.04,"pressure":1011,"sea_level":1011,"grnd_level":1010,"humidity":98,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10n"}],"clouds":{"all":100},"wind":{"speed":6.62,"deg":106},"rain":{"3h":3.81},"sys":{"pod":"n"},"dt_txt":"2019-12-30 09:00:00"},{"dt":1577707200,"main":{"temp":282.76,"feels_like":279.54,"temp_min":282.76,"temp_max":282.76,"pressure":1010,"sea_level":1010,"grnd_level":1009,"humidity":99,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10n"}],"clouds":{"all":100},"wind":{"speed":4.46,"deg":90},"rain":{"3h":9.63},"sys":{"pod":"n"},"dt_txt":"2019-12-30 12:00:00"},{"dt":1577718000,"main":{"temp":282.05,"feels_like":274.81,"temp_min":282.05,"temp_max":282.05,"pressure":1008,"sea_level":1008,"grnd_level":1008,"humidity":97,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10d"}],"clouds":{"all":100},"wind":{"speed":9.83,"deg":76},"rain":{"3h":5.69},"sys":{"pod":"d"},"dt_txt":"2019-12-30 15:00:00"},{"dt":1577728800,"main":{"temp":281.75,"feels_like":273.17,"temp_min":281.75,"temp_max":281.75,"pressure":1007,"sea_level":1007,"grnd_level":1005,"humidity":97,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10d"}],"clouds":{"all":100},"wind":{"speed":11.64,"deg":86},"rain":{"3h":4.5},"sys":{"pod":"d"},"dt_txt":"2019-12-30 18:00:00"},{"dt":1577739600,"main":{"temp":281.51,"feels_like":273.99,"temp_min":281.51,"temp_max":281.51,"pressure":1007,"sea_level":1007,"grnd_level":1006,"humidity":94,"temp_kf":0},"weather":[{"id":501,"main":"Rain","description":"moderate rain","icon":"10d"}],"clouds":{"all":100},"wind":{"speed":9.89,"deg":48},"rain":{"3h":5.88},"sys":{"pod":"d"},"dt_txt":"2019-12-30 21:00:00"},{"dt":1577750400,"main":{"temp":281.51,"feels_like":276.7,"temp_min":281.51,"temp_max":281.51,"pressure":1008,"sea_level":1008,"grnd_level":1008,"humidity":91,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":98},"wind":{"speed":5.86,"deg":41},"sys":{"pod":"n"},"dt_txt":"2019-12-31 00:00:00"},{"dt":1577761200,"main":{"temp":280.53,"feels_like":274.82,"temp_min":280.53,"temp_max":280.53,"pressure":1007,"sea_level":1007,"grnd_level":1008,"humidity":91,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":100},"wind":{"speed":6.85,"deg":25},"rain":{"3h":0.5},"sys":{"pod":"n"},"dt_txt":"2019-12-31 03:00:00"},{"dt":1577772000,"main":{"temp":280.58,"feels_like":275.82,"temp_min":280.58,"temp_max":280.58,"pressure":1007,"sea_level":1007,"grnd_level":1006,"humidity":93,"temp_kf":0},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04n"}],"clouds":{"all":62},"wind":{"speed":5.6,"deg":44},"sys":{"pod":"n"},"dt_txt":"2019-12-31 06:00:00"},{"dt":1577782800,"main":{"temp":280.13,"feels_like":275.77,"temp_min":280.13,"temp_max":280.13,"pressure":1005,"sea_level":1005,"grnd_level":1005,"humidity":93,"temp_kf":0},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04n"}],"clouds":{"all":80},"wind":{"speed":4.89,"deg":2},"sys":{"pod":"n"},"dt_txt":"2019-12-31 09:00:00"},{"dt":1577793600,"main":{"temp":280.19,"feels_like":278.17,"temp_min":280.19,"temp_max":280.19,"pressure":1005,"sea_level":1005,"grnd_level":1005,"humidity":91,"temp_kf":0},"weather":[{"id":500,"main":"Rain","description":"light rain","icon":"10n"}],"clouds":{"all":90},"wind":{"speed":1.47,"deg":354},"rain":{"3h":0.13},"sys":{"pod":"n"},"dt_txt":"2019-12-31 12:00:00"},{"dt":1577804400,"main":{"temp":280.8,"feels_like":277.47,"temp_min":280.8,"temp_max":280.8,"pressure":1005,"sea_level":1005,"grnd_level":1005,"humidity":91,"temp_kf":0},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04d"}],"clouds":{"all":75},"wind":{"speed":3.53,"deg":236},"sys":{"pod":"d"},"dt_txt":"2019-12-31 15:00:00"},{"dt":1577815200,"main":{"temp":281.92,"feels_like":275.18,"temp_min":281.92,"temp_max":281.92,"pressure":1003,"sea_level":1003,"grnd_level":1002,"humidity":66,"temp_kf":0},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03d"}],"clouds":{"all":40},"wind":{"speed":7.43,"deg":264},"sys":{"pod":"d"},"dt_txt":"2019-12-31 18:00:00"},{"dt":1577826000,"main":{"temp":281.17,"feels_like":273.88,"temp_min":281.17,"temp_max":281.17,"pressure":1002,"sea_level":1002,"grnd_level":1002,"humidity":66,"temp_kf":0},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03d"}],"clouds":{"all":35},"wind":{"speed":8.04,"deg":266},"sys":{"pod":"d"},"dt_txt":"2019-12-31 21:00:00"},{"dt":1577836800,"main":{"temp":280.75,"feels_like":273.72,"temp_min":280.75,"temp_max":280.75,"pressure":1004,"sea_level":1004,"grnd_level":1003,"humidity":66,"temp_kf":0},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04n"}],"clouds":{"all":65},"wind":{"speed":7.57,"deg":265},"sys":{"pod":"n"},"dt_txt":"2020-01-01 00:00:00"},{"dt":1577847600,"main":{"temp":279.55,"feels_like":271.26,"temp_min":279.55,"temp_max":279.55,"pressure":1003,"sea_level":1003,"grnd_level":1003,"humidity":64,"temp_kf":0},"weather":[{"id":804,"main":"Clouds","description":"overcast clouds","icon":"04n"}],"clouds":{"all":93},"wind":{"speed":9.03,"deg":272},"sys":{"pod":"n"},"dt_txt":"2020-01-01 03:00:00"},{"dt":1577858400,"main":{"temp":277.68,"feels_like":267.94,"temp_min":277.68,"temp_max":277.68,"pressure":1003,"sea_level":1003,"grnd_level":1003,"humidity":67,"temp_kf":0},"weather":[{"id":803,"main":"Clouds","description":"broken clouds","icon":"04n"}],"clouds":{"all":78},"wind":{"speed":10.87,"deg":277},"sys":{"pod":"n"},"dt_txt":"2020-01-01 06:00:00"},{"dt":1577869200,"main":{"temp":276.42,"feels_like":267.02,"temp_min":276.42,"temp_max":276.42,"pressure":1004,"sea_level":1004,"grnd_level":1004,"humidity":64,"temp_kf":0},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03n"}],"clouds":{"all":48},"wind":{"speed":10.04,"deg":274},"sys":{"pod":"n"},"dt_txt":"2020-01-01 09:00:00"},{"dt":1577880000,"main":{"temp":275.7,"feels_like":266.09,"temp_min":275.7,"temp_max":275.7,"pressure":1005,"sea_level":1005,"grnd_level":1005,"humidity":65,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02n"}],"clouds":{"all":24},"wind":{"speed":10.26,"deg":275},"sys":{"pod":"n"},"dt_txt":"2020-01-01 12:00:00"},{"dt":1577890800,"main":{"temp":275.94,"feels_like":266.39,"temp_min":275.94,"temp_max":275.94,"pressure":1006,"sea_level":1006,"grnd_level":1006,"humidity":61,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01d"}],"clouds":{"all":0},"wind":{"speed":10.08,"deg":279},"sys":{"pod":"d"},"dt_txt":"2020-01-01 15:00:00"},{"dt":1577901600,"main":{"temp":277.19,"feels_like":267.83,"temp_min":277.19,"temp_max":277.19,"pressure":1006,"sea_level":1006,"grnd_level":1006,"humidity":59,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02d"}],"clouds":{"all":23},"wind":{"speed":9.92,"deg":276},"sys":{"pod":"d"},"dt_txt":"2020-01-01 18:00:00"},{"dt":1577912400,"main":{"temp":277.93,"feels_like":268.73,"temp_min":277.93,"temp_max":277.93,"pressure":1007,"sea_level":1007,"grnd_level":1007,"humidity":58,"temp_kf":0},"weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03d"}],"clouds":{"all":28},"wind":{"speed":9.77,"deg":280},"sys":{"pod":"d"},"dt_txt":"2020-01-01 21:00:00"},{"dt":1577923200,"main":{"temp":277.49,"feels_like":267.89,"temp_min":277.49,"temp_max":277.49,"pressure":1010,"sea_level":1010,"grnd_level":1009,"humidity":61,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02n"}],"clouds":{"all":15},"wind":{"speed":10.39,"deg":284},"sys":{"pod":"n"},"dt_txt":"2020-01-02 00:00:00"},{"dt":1577934000,"main":{"temp":276.72,"feels_like":267.95,"temp_min":276.72,"temp_max":276.72,"pressure":1011,"sea_level":1011,"grnd_level":1011,"humidity":62,"temp_kf":0},"weather":[{"id":800,"main":"Clear","description":"clear sky","icon":"01n"}],"clouds":{"all":0},"wind":{"speed":9.12,"deg":287},"sys":{"pod":"n"},"dt_txt":"2020-01-02 03:00:00"},{"dt":1577944800,"main":{"temp":276.05,"feels_like":267.78,"temp_min":276.05,"temp_max":276.05,"pressure":1013,"sea_level":1013,"grnd_level":1012,"humidity":59,"temp_kf":0},"weather":[{"id":801,"main":"Clouds","description":"few clouds","icon":"02n"}],"clouds":{"all":19},"wind":{"speed":8.19,"deg":289},"sys":{"pod":"n"},"dt_txt":"2020-01-02 06:00:00"}],"city":{"id":5128581,"name":"New York","coord":{"lat":40.7143,"lon":-74.006},"country":"US","population":8175133,"timezone":-18000,"sunrise":1577535552,"sunset":1577568931}}
