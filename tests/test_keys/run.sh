#!/bin/bash
openssl genrsa -out="$1.pem" 1024 &&
openssl req -new -x509  -key "$1.pem"  -days 365 -config config.dat -out="$1.crt" &&
openssl x509 -in="$1.crt" -noout -pubkey > "$1.pub"
