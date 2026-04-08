# CodeFoundry – Kotlin execution image
# Extends eclipse-temurin:21-jdk-alpine with the Kotlin compiler (kotlinc).
# The binary fat-JAR produced by kotlinc can then be run with java -jar.

FROM eclipse-temurin:21-jdk-alpine

ARG KOTLIN_VERSION=2.0.0

RUN apk add --no-cache wget unzip \
    && wget -q \
       "https://github.com/JetBrains/kotlin/releases/download/v${KOTLIN_VERSION}/kotlin-compiler-${KOTLIN_VERSION}.zip" \
       -O /tmp/kotlin.zip \
    && unzip -q /tmp/kotlin.zip -d /opt/ \
    && rm /tmp/kotlin.zip \
    && apk del wget unzip

ENV PATH="/opt/kotlinc/bin:${PATH}"
