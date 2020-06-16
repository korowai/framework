ARG KRW_IMG
FROM ${KRW_IMG}
MAINTAINER Paweł Tomulik <ptomulik@meil.pw.edu.pl>

ARG KRW_USER='korowai'
ARG KRW_GROUP='korowai'
ARG KRW_UID=6789
ARG KRW_GID=6789
ARG KRW_HOME=/home/${KRW_USER}
ARG KRW_CODE=/code
ARG KRW_DOTENV_FILE=.env.docker
ARG KRW_BEHAT_PARAMS='{"extensions":{"Behat\\MinkExtension":{"base_url":"http://http-service"}}}'

ENV KRW_USER=$KRW_USER \
    KRW_GROUP=$KRW_GROUP \
    KRW_UID=$KRW_UID \
    KRW_GID=$KRW_GID \
    KRW_HOME=$KRW_HOME \
    KRW_CODE=$KRW_CODE \
    KRW_DOTENV_FILE=$KRW_DOTENV_FILE \
    BEHAT_PARAMS=$KRW_BEHAT_PARAMS

RUN apt-get update && \
    apt-get install -y --no-install-recommends mariadb-client && \
    apt-get clean && \
    docker-php-ext-install pdo pdo_mysql mysqli && \
    rm -rf /var/lib/apt/lists/* && \
    if [ -f /etc/alpine-release ]; then \
      addgroup -g $KRW_GID $KRW_GROUP && \
      adduser -h $KRW_HOME -G $KRW_GROUP -u $KRW_UID -D $KRW_USER; \
    else \
      groupadd -g $KRW_GID $KRW_GROUP && \
      useradd -m -d $KRW_HOME -g $KRW_GROUP --uid $KRW_UID $KRW_USER; \
    fi; \
    mkdir -p $KRW_CODE && chown $KRW_USER:$KRW_GROUP $KRW_CODE && \
    if [ -e "/tmp/cc" ]; then chown -R $KRW_USER:$KRW_GROUP "/tmp/cc"; fi

USER $KRW_USER

VOLUME $KRW_CODE

WORKDIR $KRW_CODE

# vim: set ft=dockerfile:
