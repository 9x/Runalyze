# [Runalyze v3.0](http://blog.runalyze.com)

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/Runalyze/Runalyze?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://travis-ci.org/Runalyze/Runalyze.svg?branch=master)](https://travis-ci.org/Runalyze/Runalyze)


Runalyze is a web application for analyzing your training - more detailed than any other sports diary.  
Runalyze is mainly developed by [laufhannes](https://github.com/laufhannes) and [mipapo](https://github.com/mipapo).

## Runalyze with Symfony
We're planning to migrate to Symfony2.
This branch will be simultaneously maintained with our current work on v2.1.

## Install / Development
The development of Runalyze v3+ requires [composer](https://getcomposer.org/doc/00-intro.md#system-requirements),
[npm](https://nodejs.org/download/), [bower](http://bower.io/) (`sudo npm install -g bower`) and
[grunt](http://gruntjs.com/) (`sudo npm install -g grunt-cli`).

To install all dependencies:
```
composer install
bower install
npm install
grunt
```

## License
* TODO - we're currently seeking for the right license to choose

## Changelog
* new versions, multi-lingual
 * [v2.0.2](http://blog.runalyze.com/allgemein/runalyze-v2-0-2/), 24.03.2015: Minor bugfixes
 * [v2.0.1](http://blog.runalyze.com/allgemein/runalyze-v2-0-1/), 13.03.2015: Minor bugfixes
 * [v2.0](http://blog.runalyze.com/allgemein/runalyze-v2-0/), 28.02.2015: First mutlilingual major release
* old versions, only in german
 * [v1.5](http://blog.runalyze.com/allgemein/runalyze-v1-5/), 01.01.2014: Bugfixes, genauere VDOT-Formel
 * [v1.4](http://blog.runalyze.com/allgemein/runalyze-v1-4-fix-fuer-sicherheitsproblem/), 23.08.2013: Bugfix für Sicherheitsrisiko
 * [v1.3](http://blog.runalyze.com/allgemein/runalyze-v1-3/), 29.07.2013: Neue Importer, mehr Trainingsdaten, VDOT-Korrektur für Höhenmeter, ...
 * [v1.2](http://blog.runalyze.com/allgemein/runalyze-v1-2/), 13.11.2012: Diagramme speichern, Öffentliche Trainingsliste
 * [v1.1](http://blog.runalyze.com/allgemein/runalyze-v1-1/), 19.07.2012: Erste Online-Version
 * [v1.0](http://blog.runalyze.com/allgemein/runalyze-v1-0/), 20.01.2012: Erste öffentliche Version

## Credits
* Icons
	* [Font Awesome](http://fontawesome.io/) by Dave Gandy
	* [Forecast Font](http://forecastfont.iconvau.lt/) by Ali Sisk
	* [Icons8 Font](http://icons8.com/) by VisualPharm
* Elevation data from Shuttle Radar Topography Mission
	* [SRTM tiles](http://dwtkns.com/srtm/) grabbed via Derek Watkins
	* [SRTM files](http://srtm.csi.cgiar.org/) by International  Centre for Tropical  Agriculture (CIAT)
	* [SRTMGeoTIFFReader](http://www.osola.org.uk/elevations/index.htm) by Bob Osola
* [jQuery](http://jquery.org/) by jQuery Foundation, Inc.
    * [Bootstrap Tooltip](http://twitter.github.com/bootstrap/javascript.html#tooltips) by Twitter, Inc.
    * [Flot](http://www.flotcharts.org/) by IOLA and Ole Laursen
    * [Leaflet](http://leafletjs.com/) by Vladimir Agafonkin
    * [FineUploader](https://github.com/Widen/fine-uploader) by Widen Enterprises, Inc.
    * [Tablesorter](http://tablesorter.com/docs/) by Christian Bach
    * [Datepicker](http://www.eyecon.ro/) by Stefan Petre
    * [Chosen](http://getharvest.com/) by Patrick Filler for Harvest
    * [FontIconPicker](http://codeb.it/) by Alessandro Benoit &amp; Swashata Ghosh
* Miscellaneaous
    * [phpFastCache](https://github.com/khoaofgod/phpfastcache) by Khoa Bui
    * [Garmin Communicator](http://developer.garmin.com/web-device/garmin-communicator-plugin/) by Garmin Ltd.
    * [Weather data](http://openweathermap.org) from OpenWeatherMap Inc.
