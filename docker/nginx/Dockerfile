ARG NGINX_VERSION=latest

FROM nginx:${NGINX_VERSION}

RUN mv -f /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.bak

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/certs/local.crt /etc/nginx/certs/local.crt
COPY ./docker/nginx/certs/local.key /etc/nginx/certs/local.key
