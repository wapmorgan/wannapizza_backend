FROM php:7.4-fpm

RUN apt-get update && apt-get -y install cron

# Copy hello-cron file to the cron.d directory
COPY crawl-pizzerias /etc/cron.d/crawl-pizzerias

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/crawl-pizzerias

# Apply cron job
RUN crontab /etc/cron.d/crawl-pizzerias

COPY crawl-dodo.php /var/crawler/
COPY crawl-dominos.php /var/crawler/
COPY crawl-papajohns.php /var/crawler/

WORKDIR /var/crawler

# Run the command on container startup
CMD ["cron", "-f"]
