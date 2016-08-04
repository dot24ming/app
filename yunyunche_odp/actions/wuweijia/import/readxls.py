#!/usr/bin/env python
#coding:utf-8

import xlrd

data = xlrd.open_workbook('20160731-meichehui.xls')
table = data.sheets()[0]
for rownum in range(table.nrows):
	#for attr in table.row_values(rownum):
	print '\t'.join(table.row_values(rownum)).encode('utf-8')

	'''
	attr_list = table.row_values(rownum)

	goods_id = attr_list[0]
	name = attr_list[1].encode('utf-8')
	category = attr_list[4].encode('utf-8')
	spec = 
	unit = 
	ser_num = 
	super_id = 
	instock_count = 
	instock_price = 
	instock_avg = 
	reference_price = 
	reference_cost = 
	'''



