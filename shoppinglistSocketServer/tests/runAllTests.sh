#!/bin/sh

# Loop through the test files in the test directory and run them as PHPUnit
cd ..
for f in tests/test*.php
do
   phpunit --verbose --colors $f
done
