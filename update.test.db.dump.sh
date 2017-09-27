#!/bin/bash
cd /var/www/companies/tests/_data/json
for f in *; do
  mongoimport -h mongo --db test --file "$f" --drop --jsonArray
done
cd /var/www/companies/tests/_data
mongodump -h mongo --db test
cd /var/www/companies/tests/_data/dump
tar -czf apidump.tar.gz test
cp -r apidump.tar.gz /var/www/companies/tests/_data/apidump.tar.gz
