# Pimcore Project Skeleton 

### Frameworks
- Symfony 6
- Pimcore 11

## Required

- **PHP** vers. >= 8.3.0
- **Composer** vers. >= 2.7.2
- **npm** vers. >= 6.14.4
- **docker** vers. >= 24.0.5
- **docker compose** vers. >= 2.26.1
- **curl** vers. >= 27.68.0

## Create docker container and pimcore app
```bash
# Change into project folder or open in your IDE
cd ./PimSkel-docker

# Build docker container
$ docker compose up -d

# When all docker container startet
# Install vendor enviroment
$ docker compose exec php composer install

# Install pimcore
$ docker compose exec php vendor/bin/pimcore-install
# This will ask you for admin name: pimcore
# And a admin password: pimcore
# Allow all Bundles
# By success you see see this messages
[OK] Pimcore was successfully installed 

# Import the pimcore database from /var/backups/pimcore.sql
$ ./dump_import.sh

```

### Create a document 'Products' in pimcore admin
```bash
# Open your pimcore admin interface localhost:8087/admin
$ Documents -> Home -> Add Page -> ProductType
Title: Products
key: products

# Change the controller action to indexAction
$ Documents -> Home -> products -> Seo & Settings -> Controller, Action & Template
Controller: App\Controller\ProductController::indexAction

Save & Publish

# Open your browser 
$Url: localhost:8087/products

- Add new product (name,description)
- Or edit delete products
```

### Helper

```bash
# Problem with php extension, 
# this command shows our php extensions
$ php --ini

# Check container logs
$ docker logs [OPTIONS] CONTAINER

# Clear symfony cache
$ ./cache-clr.sh

# Remove  all stopped docker container and images
$ ./docker-cleanup.sh
```


### Prerequisites

* Your user must be allowed to run docker commands (directly or via sudo).
* You must have docker compose installed.
* Your user must be allowed to change file permissions (directly or via sudo).



### Run codeception tests:
   * `docker compose run --user=root --rm test-php chown -R $(id -u):$(id -g) var/ public/var/`
   * `docker compose run --rm test-php vendor/bin/pimcore-install -n`
   * `docker compose run --rm test-php vendor/bin/codecept run -vv`

### heavy_check_mark: DONE - You can now visit your pimcore instance:
    * The frontend: 
        <http://localhost:8087>
    * The admin interface, using the credentials you have chosen above:
        <http://localhost:8087/admin>


