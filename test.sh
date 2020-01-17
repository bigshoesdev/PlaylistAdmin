#!/bin/bash
useradd -G www-data -m $1
passwd $1

sh newsite.sh create coinmama.motowelove.com
