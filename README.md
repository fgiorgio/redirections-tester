# Domain Redirections Tester

A simple utility for Web Developers and SEO Managers that helps testing 
domain redirections and some URL rewriting rules.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine
for development and testing purposes. See [deployment section](#deployment) for notes on how 
to deploy the project on a live system.

### Prerequisites

You can use one of the following to create a local web server:

* [Docker](https://www.docker.com/) - A set of platform-as-a-service (PaaS) products that use
OS-level virtualization to deliver software in packages called containers.
* [Docker Compose](https://docs.docker.com/compose/) - A tool for defining and running 
    multi-container Docker applications.
    
or
    
* [XAMPP](https://www.apachefriends.org/) - A completely free, easy to install Apache 
distribution containing MariaDB, PHP, and Perl.

or any other environment with almost:

* PHP 7.0+
* cURL extension enabled

### Installing

If you have Git installed on your computer you can clone this project with the following command:

```
git clone https://github.com/fgiorgio/redirections-tester.git
```

or download it manually otherwise.

##### Docker

Verify that you have installed Docker and Docker Compose on your computer with the following commands:

```
docker -v
docker-compose -v
```

The project contains all the configuration files to build the required containers. Open the project root directory and launch the command:

```
docker-compose up -d --build
```

This should build and run a complete environment with nginx web server, PHP 7 and cURL extension enabled. Check them with the following commands:

```
docker exec redirections-tester-app php -v
docker exec redirections-tester-app php -m
```

##### XAMPP or other local web server

Check for the installed PHP version and cURL extension with the following commands:

```
php -v
php -m
```

Copy the content of `src` directory on your local web server.

## Run

* Open your favourite browser
* Point to local web server (`127.0.0.1` or where project files are located)
* Follow the instructions

## Deployment

You need a web server with the specifics listed in the [Prerequisites section](#prerequisites).

* Copy the content of `src` directory on your web server.
* Point your browser to the web server, where project files are located
* Follow the instructions

## Built With

* [Bootstrap](https://getbootstrap.com/) - the front-end component library used
* [DataTables](https://datatables.net/) - a plugin for HTML tables
* [FontAwesome](https://fontawesome.com/) - icon set and toolkit

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/fgiorgio/redirections-tester/tags). 

## Authors

* **Francesco Giorgio** - [giorgio.dev](https://giorgio.dev)

See also the list of [contributors](https://github.com/fgiorgio/redirections-tester/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details