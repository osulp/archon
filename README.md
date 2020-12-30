# Archon
Customized version of Archon 3.21 created for Oregon State University's Special Collections and Archives Research Center

Custom theme: scarc

### Docker
* docker-compose build
* docker-compose up
* visit localhost:8080 (should hit index.php)
* follow instructions in Archon installer
* when successful, run `docker exec archon_webserver_1 ./docker-installdone.sh`
* login using created user
* visit Archon Administration > Archon Configuration > Default Theme > change Value to 'scarc'
* visit Archon Administration > Archon Configuration > Default Template Set > change Value to 'scarc'
