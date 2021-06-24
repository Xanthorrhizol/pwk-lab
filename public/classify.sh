#!/bin/bash
for ip in $(ls | grep '10.11.1'); do
  res=$(cat $ip/$ip.nmap | wc -l)
  if [ $res -eq 3 ]; then
    mv $ip closed/
  fi
done
