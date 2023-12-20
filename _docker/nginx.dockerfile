FROM nginx:1.23.4-alpine

RUN apk add --no-cache \
    openssl \
    bash