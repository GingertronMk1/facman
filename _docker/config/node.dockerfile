FROM node:21-alpine3.18

RUN apk add --no-cache \
    bash

WORKDIR /app

ENTRYPOINT ["tail", "-f", "/dev/null"]
