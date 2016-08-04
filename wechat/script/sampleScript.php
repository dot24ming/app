<?php
/**
 * @name sampleScript
 * @desc 示例脚本
 * @author 
 */
Bd_Init::init();

$openId = 'ocp0os7FbEaRiW0k0oiKLamnq1x0';
//主体功能逻辑写在这里
#$ret = Tool_WeiXin::genLimitQrUrl("0_1");
#var_dump($ret);
#$ret = Tool_WeiXin::genLimitQrUrl("0_1");
#var_dump($ret);
//$ret = Tool_WeiXin::getToken();
//$ret = Tool_WeiXin::sendPicMessage($openId, 1);
$ret = Tool_WeiXin::moveToTeam($openId, 100);
var_dump($ret);



//如果利用noah ct任务系统运行脚本，需要显示退出，设置退出码为0，否则监控系统会报警
exit(0);
