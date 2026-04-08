# CodeFoundry – Lua execution image
# Alpine base with Lua 5.4 installed and aliased as "lua".

FROM alpine:3.19

RUN apk add --no-cache lua5.4 \
    && ln -sf /usr/bin/lua5.4 /usr/local/bin/lua
