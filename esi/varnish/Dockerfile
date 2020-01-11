FROM alpine:3.11

EXPOSE 80

ENV VCL_CONFIG      /etc/varnish/default.vcl
ENV CACHE_SIZE      64m
ENV VARNISHD_PARAMS ""

RUN apk update && \
    apk upgrade && \
    apk add varnish

ADD start.sh /start.sh
CMD ["/start.sh"]
