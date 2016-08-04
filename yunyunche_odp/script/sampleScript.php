<?php
/**
 * @name sampleScript
 * @desc 示例脚本
 * @author 吴伟佳
 */
$obj = Bd_Init::init();
Tool_Const::$storeId = 56;
$goodsInfoDao = new Dao_GoodsInfo();
$list = $goodsInfoDao->getInfoBySerNum("uye91321");
var_dump($list);
//如果利用noah ct任务系统运行脚本，需要显示退出，设置退出码为0，否则监控系统会报警
exit(0);
