FROM silintl/php7:latest
MAINTAINER Phillip Shipley <phillip_shipley@sil.org>

ENV REFRESHED_AT 2016-04-22

COPY dockerbuild/vhost.conf /etc/apache2/sites-enabled/

RUN mkdir -p /data

# Copy in syslog config
RUN rm -f /etc/rsyslog.d/*
COPY dockerbuild/rsyslog.conf /etc/rsyslog.conf
RUN mkdir -p /opt/ssl
COPY dockerbuild/logentries.all.crt /opt/ssl/logentries.all.crt

# get s3-expand
RUN curl https://raw.githubusercontent.com/silinternational/s3-expand/1.5/s3-expand -o /usr/local/bin/s3-expand
RUN chmod a+x /usr/local/bin/s3-expand

# Install/cleanup composer dependencies
COPY application/composer.json /data/
COPY application/composer.lock /data/
RUN composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader

# It is expected that /data is = application/ in project folder
COPY application/ /data/

WORKDIR /data

# Fix folder permissions
RUN chown -R www-data:www-data \
    console/runtime/ \
    frontend/runtime/ \
    frontend/web/assets/

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/s3-expand"]
CMD ["/data/run.sh"]
