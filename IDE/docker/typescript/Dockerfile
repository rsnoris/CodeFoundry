# CodeFoundry – TypeScript execution image
# Extends the official Node.js 20 slim image with TypeScript and ts-node
# installed globally so scripts can be run offline (no network at runtime).

FROM node:20-slim

RUN npm install -g typescript ts-node \
    && npm cache clean --force
