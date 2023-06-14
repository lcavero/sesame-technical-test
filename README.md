# Sesame Technical Test

A Symfony test project made with CQRS, Hexagonal Architecture and DDD.

The purpose of the project is to solve the test presented by Sesame, the statement of which can be found in the "docs" folder.

You can find an up-to-date Postman collection of the endpoints in the "docs" folder

Created from [Symfony Docker](https://github.com/dunglas/symfony-docker) project as starting point.

![CI](https://github.com/lcavero/sesame-technical-test/workflows/CI/badge.svg)
## Build project

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell)
4. Run migrations (only first time) `make run-migrations`
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Makefile

You can use make commands included in Makefile to run common actions like:

- Up containers: make up
- Down containers: make down
- Enter in php container: make sh
- Run migrations: make run-migrations
- Run tests: make run-tests
