FROM postgis/postgis:latest

ENV POSTGRES_PASSWORD docker
ENV POSTGRES_DB db
ENV POSTGRES_USER docker

COPY . /app/database
COPY docker/db/init-scripts/ /docker-entrypoint-initdb.d/