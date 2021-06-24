#!/bin/bash
for ip in $(ls | grep .nmap | replace '.nmap' ''); do
  mkdir $ip
  mv $ip.nmap $ip/
done
