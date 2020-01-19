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

ENV KRW_USER=$KRW_USER \
    KRW_GROUP=$KRW_GROUP \
    KRW_UID=$KRW_UID \
    KRW_GID=$KRW_GID \
    KRW_HOME=$KRW_HOME \
    KRW_CODE=$KRW_CODE \
    KRW_DOTENV_FILE=$KRW_DOTENV_FILE \
    APACHE_RUN_USER=$KRW_USER \
    APACHE_RUN_GROUP=$KRW_GROUP \
    APACHE_DOCUMENT_ROOT=$KRW_CODE/public

RUN if [ -f /etc/alpine-release ]; then \
      addgroup -g $KRW_GID $KRW_GROUP && \
      adduser -h $KRW_HOME -G $KRW_GROUP -u $KRW_UID -D $KRW_USER && \
      addgroup $KRW_USER ssl-cert; \
    else \
      groupadd -g $KRW_GID $KRW_GROUP && \
      useradd -m -d $KRW_HOME -g $KRW_GROUP -G ssl-cert --uid $KRW_UID $KRW_USER; \
    fi; \
    mkdir -p $KRW_CODE && chown $KRW_USER:$KRW_GROUP $KRW_CODE && \
    echo "net.ipv4.ip_unprivileged_port_start=0" > /etc/sysctl.d/99-korowai.conf && \
    sysctl -p && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

VOLUME $KRW_CODE

WORKDIR $KRW_CODE

# vim: set ft=dockerfile:
