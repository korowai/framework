FROM korowai/php:cli
MAINTAINER Paweł Tomulik <ptomulik@meil.pw.edu.pl>

ARG PHP_USER='php'
ARG PHP_GROUP='php'
ARG PHP_UID=1000
ARG PHP_GID=1000
ARG PHP_HOME='/home/php'
ARG PHP_PROJECT='/home/php/project'

ENV PHP_USER=$PHP_USER \
    PHP_GROUP=$PHP_GROUP \
    PHP_UID=$PHP_UID \
    PHP_GID=$PHP_GID \
    PHP_HOME=$PHP_HOME \
    PHP_PROJECT=$PHP_PROJECT

RUN groupadd -g $PHP_GID $PHP_GROUP && \
    useradd -m -d $PHP_HOME -g $PHP_GROUP --uid $PHP_UID $PHP_USER

VOLUME $PHP_PROJECT

USER $PHP_USER

WORKDIR $PHP_PROJECT

# vim: set ft=dockerfile:
