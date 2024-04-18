FROM postgres:alpine

ENV POSTGRES_USER=main
ENV POSTGRES_PASSWORD=main
ENV POSTGRES_DB=main

COPY ./init.sql /docker-entrypoint-initdb.d/init.sql