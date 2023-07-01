FROM node:20-alpine3.16

RUN apk add --no-cache \
    bash

WORKDIR /app
