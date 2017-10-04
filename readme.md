# Jincor Companies
 ![](https://habrastorage.org/webt/59/d5/42/59d542206afbe280817420.png)

 This service provides a functionality for managing companies and
 employees in the network for any purpose. Jincor uses this service
 to connect real-world companies to the blockchain, provide identifiers
 to companies, departments and employees. It is also responsible for idenetification of the companies(it creates identifiers).
 Read API docs in `apiary.apib` file in the root folder.
 
 ## Prerequisites
 
 To set up and run the project you will need docker engine and
 docker-compose.

## Key dependencies

* [Dingo API](https://github.com/dingo/api/wiki) - helps to create API's
* [CodeCeption](http://codeception.com/quickstart) - Acceptance, Unit and Functional testing
* [Doctrine MongoDB ODM](http://docs.doctrine-project.org/projects/doctrine-mongodb-odm/en/latest/) - Object Document Mapper


## Architecture notes

1. Code of application is located in the `app/` folder.
2. Configuration files are in `config/` folder. Environment-specific configuration(such as database connections, email providers etc) should be set in `.env` file. Example of `.env` file can be find in the root of the project `.env.example`
3. Application is conceptually separated by 3 parts:
 * Application(mixed with Interfaces layer) - The application layer is responsible for driving the workflow of the application, matching the use cases at hand. These operatios are interface-independent and can be both synchronous or message-driven. This layer is well suited for spanning transactions, high-level logging and security.The application layer is thin in terms of domain logic - it merely coordinates the domain layer objects to perform the actual work.
 * Core - Infrastructure layer consists of everything that exists independently of our application: external libraries, database engine, application server, messaging backend and so on. Also, we consider code and configuration files that glues the other layers to the infrastructure as part of the infrastructure layer. Doctrine configuration and mapping files and implementations of the repository interfaces are part of the infrastructure layer.
 * Domain - The domain layer is the heart of the software, and this is where the interesting stuff happens. There is one package per aggregate, and to each aggregate belongs entities, value objects, domain events, a repository interface and sometimes factories. The structure and naming of aggregates, classes and methods in the domain layer should follow the ubiquitous language, and you should be able to explain to a domain expert how this part of the software works by drawing a few simple diagrams and using the actual class and method names of the source code.
 
 The code from Domain folder must not depend on the framework, 
 core and application code. The code from Core folder may depend on Domain but
 must not depend on Application. Application depends on Core and Domain.

### Local development setup
1. Clone the repo
2. `cd /path/to/repo` - go to the projects folder
3. `docker-compose build` - build development containers
4. Wait for a while
5. `docker-compose up -d` - run services
5. `docker-compose exec workspace bash`
5. You need to register company service as tenant, run in "workspace" container:
```
curl --include \
     --request POST \
     --header "Content-Type: application/json" \
     --header "Accept: application/json" \
     --data-binary "{
    \"email\": \"test@test.com\",
    \"password\": \"Password1\"
}" \
'http://auth:3000/tenant'
``` 
8. Then login tenant:
```
curl --include \
     --request POST \
     --header "Content-Type: application/json" \
     --header "Accept: application/json" \
     --data-binary "{
    \"email\": \"test@test.com\",
    \"password\": \"Password1\"
}" \
'http://auth:3000/tenant/login'
```
9. Add received JWT to .env file as IDENTITY_JWT.
10. `./init.sh` - installs composer dependencies, set up environment variables and runs `php artisan db:seed`

Your changes will be automatically synchronized with local machine, there is no need to rebuild or restart containers after source code changes.

*Exception* - you changed something related to docker(dockerfiles, docker-compose, etc)

*Note that you don't need to do step 6 and bellow except the very first time. Otherwise it will erase all your saved data!*

Check **auth service** documentation for more info about auth API: http://docs.jincorauthservice.apiary.io/

#### Local testing

To run *unit* tests just type `docker-compose exec workspace ./vendor/bin/codecept run unit`
You can add --coverage option to get the code-coverage report on Unit Tests

To run *API* tests just type `docker-compose exec workspace ./vendor/bin/codecept run api`


## Authorization
We use JWT for authentication and authorization. Refer to JWT documentation in order to get more
info about JWT itself. We support only `Bearer` tokens which must be sent in HTTP-headers.
Most of API methods require authorization and not accessible without JWT. But most of dictionaries
can be accessed by anyone.

## Company registration process

Company registration process is built around the concept of verification sessions.
When someone registers a company, he or she forced to create the first account in
this company.

According to business requirements system should allow users to have
multiple accounts attached to one email. So, here everything is pretty like in
classical multy-tenant saas application - unqie profile is not only it's own id but
the tenant id as well. So in our case email + companyId.

To make registration process more configurable we use Entity called `VerificationProcess`
which has unique key which you must provide to server when doing requests during verification process.
Note that `VerificationProcess` also keeps all the data required to restore the session, think about it like about session key.

In order to achieve business requirement which shortly can be described as: 'Trigger user registration after company registration'
I've moved creating of `VerificationProcess` Entity to the end of company registration workflow.
So, remember: The result of company registration is always `VerificationProcess`.


## Login process

At first time the whole login system can look too complicated that's why we added this section
to our docs to get you through the login process quickly.
1 user(means 1 email address) can be associated to many companies. Because of some reasons
like security and functionality we decided to keep such kind of accounts independently.
But this decion leads us to another problem: you must specify company you are logging in
in every login request.
The login workflow is looks like this:
1. Get the list of companies from the server by email and password
2. Pick of the companies and send login request with email, password and companyId

Or if you don't have email&password you can use alternative authentication method with
VerificationProcess and verificationId. Just replace email&password by verificationId and
you are done.


## VerificationProcess notes
There is a concept of VerificationProcess in our app which should be mentioned. In order to verify
the ownership of email address or mobile phone we use verification codes. I found out that this codes
may be usable in such cases as: registration, restore password, invite employee. All this cases
are same in general but different in some details, but the process of verification of ownership is 100%
same. So I've creared a model called VerificationProcess which represents exact this thing.
It contains some helpfull references like reference to company or employee it belongs to,
email address, phone number and information about it's statuses(verified: true, false).
Just remember that if you need to do some actions with employee but you don't have email/password
you need to checkout VerificationProcess first.