# UBN Backend Monolith

Built on [hlogeon/LaravelBoilerplate](https://github.com/hlogeon/LaravelBoilerplate)

## Important libraries included

* [Dingo API](https://github.com/dingo/api/wiki) - helps to create API's
* [CodeCeption](http://codeception.com/quickstart) - Acceptance, Unit and Functional testing
* [Doctrine MongoDB ODM](http://docs.doctrine-project.org/projects/doctrine-mongodb-odm/en/latest/) - Object Document Mapper
* [Swagger](http://swagger.io/) - API documentation tool
* [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) - Swagger integration for Laravel. Adding support of generating APIDocs from PHP Annotations using [Doctrine Annotations](https://github.com/doctrine/annotations)

## Architecture notes

1. Code of application is inside `app/` folder.
2. Configuration files are in `config/` folder. Environment-specific configuration(such as database connections, email providers etc) should be set in `.env` file. Example of `.env` file can be find in the root of the project `.env.example`
3. This boilerplate is using 3-layer DDD architecture and the layers are
 * Application(mixed with Interfaces layer) - The application layer is responsible for driving the workflow of the application, matching the use cases at hand. These operatios are interface-independent and can be both synchronous or message-driven. This layer is well suited for spanning transactions, high-level logging and security.The application layer is thin in terms of domain logic - it merely coordinates the domain layer objects to perform the actual work.
 * Infrastructure(called Core in our case) - In simple terms, the infrastructure consists of everything that exists independently of our application: external libraries, database engine, application server, messaging backend and so on. Also, we consider code and configuration files that glues the other layers to the infrastructure as part of the infrastructure layer. Doctrine configuration and mapping files and implementations of the repository interfaces are part of the infrastructure layer.
 * Domain - The domain layer is the heart of the software, and this is where the interesting stuff happens. There is one package per aggregate, and to each aggregate belongs entities, value objects, domain events, a repository interface and sometimes factories. The structure and naming of aggregates, classes and methods in the domain layer should follow the ubiquitous language, and you should be able to explain to a domain expert how this part of the software works by drawing a few simple diagrams and using the actual class and method names of the source code.

[layers](http://image.prntscr.com/image/f65fc6d827a24b849f6a13e87b687227.jpg "Layers")

## Project setup

To set up and run the project you will need docker engine,
docker-compose. Also you must be logged in to
`registry.jincor.com`. You can find your credentials in LastPass.

To login to registry type `docker login registry.jincor.com -u %user_name% -p%password%`

### Local development setup
1. Clone the repo
2. `cd /path/to/repo` - go to the projects folder
3. `docker-compose build` - build development containers
4. Wait for a while
5. `docker-compose up -d` - run services
6. `docker-compose exec workspace ./init.sh` - installs composer dependencies, set up environment variables and runs `php artisan db:seed`

Your changes will be automatically synchronized with local machine, there is no need to rebuild or restart containers after source code changes.

*Exception* - you changed something related to docker(dockerfiles, docker-compose, etc)

*Note that you don't need to do step 6 except the very first time. Otherwise it will erase all your saved data!*



#### Local testing

To run *unit* tests just type `docker-compose exec workspace ./vendor/bin/codecept run unit`
You can add --coverage option to get the code-coverage report on Unit Tests

To run *API* tests just type `docker-compose exec workspace ./vendor/bin/codecept run api`


## Service Development Workflow

1. Create branch
2. Do your changes
3. Run tests
4. Create Pull Request
5. Wait for approve
6. Merge