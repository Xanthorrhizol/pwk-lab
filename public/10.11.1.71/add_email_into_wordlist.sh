#!/bin/bash
for line in $(cat bigtree_passlist.cewl); do
  echo ${line}@bigtreecms.org >> bigtree_userlist.cewl
done
