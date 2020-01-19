# Korowai Framework

[![Build Status (TravisCI)](https://travis-ci.org/korowai/framework.svg?branch=master)](https://travis-ci.org/korowai/framework)
[![BUild Status (CircleCI)](https://circleci.com/gh/korowai/framework.svg?style=svg)](https://circleci.com/gh/korowai/framework)
[![Codecov](https://codecov.io/gh/korowai/framework/branch/master/graph/badge.svg)](https://codecov.io/gh/korowai/framework)
[![Maintainability](https://api.codeclimate.com/v1/badges/e022fc1ea75dbbe42966/maintainability)](https://codeclimate.com/github/korowai/framework/maintainability)
[![Documentation Status](https://readthedocs.org/projects/korowai-framework/badge/?version=latest)](https://korowai-framework.readthedocs.io/en/latest/?badge=latest)

Open Source software for exploring LDAP directories.

## Why Korowai?

The Korowai, also called the Kolufo, are the people who live in southeastern
West Papua in the Indonesian Province of Papua, close to the border with Papua
New Guinea. The majority of the Korowai clans live in tree houses on their
isolated territory.

Korowai life highly dependent on trees. Similarly, some admins' work depends on
LDAP database, which has tree structure.

## Online documentation

See [documentation on ReadTheDocs](https://korowai-framework.readthedocs.io/).

## Notes for users

### Runtime Requirements

PHP>=7.1.3 is required. In addition to PHP, you may need to install these
packages

```shell
apt-get install php-ldap php-xml php-mbstring
```

## Notes for developers

### Development requirements

- [docker](https://docker.com)

### Initial preparations

After you've just cloned

```shell
php bootstrap-dev && php bin/composer install
```

### Running unit tests

```shell
php vendor/bin/phpunit
```

### Running integration tests

```shell
docker/compose run --rm php-test vendor/bin/behat
docker/compose down
```

### Running sphinx examples tests

```shell
docker/compose run --rm php-test vendor/bin/behat -c docs/behat.yml
docker/compose down
```

### Running interactive PHP shell

```shell
docker/compose run --rm php-cli vendor/bin/psysh
```

### Running CodeClimate

```shell
docker/codeclimate run --rm codeclimate analyze
docker/codeclimate down
```

### Generating API documentation

```shell
docker/docs run --rm sami build
docker/docs down
```

The generated API docs go to ``docs/build/html/api/``.

### Generating API documentation continuously and serving via HTTP

```shell
docker/docs up sami
docker/docs down
```

The generated API docs go to ``docs/build/html/api/`` and get exposed at

  - ``https://localhost:8001``.

### Generating sphinx documentation continuously and serving via HTTP

```shell
docker/docs up sphinx
docker/docs down
```

The generated docs go to ``docs/build/html`` and get exposed at

  - ``http://localhost:8002``.
