#!/bin/bash
#for octat in 5 7 8 10 13 14 20 21 22 24 31 35 39 44 50 71 72 73 75 79 101 111 115 116 118 120 121 122 123 128 133 136 141 146 209 217 220 221 222 223 227 229 231 234 237 247 251 252; do
for octat in 221 222 223 227 229 231 234 237 247 251 252; do
	sudo nmap -sC -sS -p0-65535 10.11.1.$octat > 10.11.1.$octat.nmap
done
#ps=$(ps -ef | grep openvpn | grep root | awk '{ print $2 }' | head -1)
#kill $ps
#sleep 3
echo "all process has successfully done >> scan_success.txt"
#shutdown -h now
