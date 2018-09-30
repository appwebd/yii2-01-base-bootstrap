#!/bin/bash
rm web/css/style.min.css
rm web/javascript/javascript-distr.min.js
#rm cache/*
gulp concatenation
#gulp uncss
gulp creating_file_public_css
gulp concat_and_minify_javascript
