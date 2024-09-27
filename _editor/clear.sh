#!/usr/bin/env bash

find ./ -name "*.min.js"  -depth -exec rm -v {} \;
find ./ -name "*.min.css" -depth -exec rm -v {} \;
find ./ -name "*.css.map" -depth -exec rm -v {} \;
