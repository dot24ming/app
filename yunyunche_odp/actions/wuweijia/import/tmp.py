#!/usr/bin/env python
#coding:utf-8

import sys

for line in sys.stdin:
	line = line.strip()
	line = line.rstrip(',')
	print line
