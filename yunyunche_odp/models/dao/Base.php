<?php
/**
 * @name Dao_Base
 * @desc 数据库访问的父类，所有数据库访问dao 都继承这个类
 * @author 朴红吉(piaohongji@baidu.com)
 */
class Dao_Base {
	const TABLE_FORMAT = "%s_%s";

	// db general error
    const ERRORNO_DB_GENERAL_ERROR = 201;
    
    // db conn error
    const ERRORNO_DB_CONN_ERROR = 202;
    
    // sql assember error
    const ERRORNO_DB_ASSEMBER_ERROR = 203;
    
    // sql query failed
    const ERRORNO_DB_QUERY_FAILED = 204;
    
    // duplicate entry
    const ERRORNO_DB_DUPLICATE_ENTRY = 205;

    /**
     * 数据库对象
     */
    protected $objDB;
    
    /**
     * SQLAssemer 对象
     */
    protected $objSQLAssember;
    
    public function __construct() {
        $o = self::getConn("ClusterOne");
		
        $this->objDB = $o->db;
        $this->objSQLAssember = $o->sqla;
    }

	private function getConn($strName) {
		$oDB = Bd_Db_ConnMgr::getConn($strName);
        if ($oDB === false) {
            throw new Exception('db conn error(check dbral log)', self::ERRORNO_DB_CONN_ERROR);
        } else {
            $oSQLAssember = new Bd_Db_SQLAssember($oDB);
            if ($oSQLAssember === false) {
                throw new Exception('new sqlassember error', self::ERRORNO_DB_ASSEMBER_ERROR);
            } else {
                $oRet = (object) array();
                $oRet->db = $oDB;
                $oRet->sqla = $oSQLAssember;
                return $oRet;
            }   
        }   
	}
    
    /**
     * 执行一个数据库查询，返回查询结果
     */
    public function query($strSQL) {
        if ($this->objDB) {
            $arrDBRet = $this->objDB->query($strSQL);
            $errno = $this->objDB->errno();
            $error = $this->objDB->error();
            if ($arrDBRet === false) {
                // 对于唯一索引冲突（1062），单独提示一下
                if ($errno === 1062) {
                    throw new Exception('query failed(sql=' . $strSQL . ')', self::ERRORNO_DB_DUPLICATE_ENTRY);
                }
                throw new Exception('query failed(sql=' . $strSQL . ')', self::ERRORNO_DB_QUERY_FAILED);
            } else {
                return $arrDBRet;
            }
        }
        throw new Exception('objDB in Dao_Base is not an object', self::ERRORNO_DB_GENERAL_ERROR);
    }
    
    /**
     * 执行一个数据库查询，同时查询SQL_CALC_FOUND_ROWS
     */
    protected function queryWithCount($strSQL, &$intFoundRows) {
        if ($this->objDB) {
            $arrDBRet = $this->objDB->query($strSQL);
            if ($arrDBRet === false) {
                throw new Exception('query failed(sql=' . $strSQL . ')', self::ERRORNO_DB_QUERY_FAILED);
            } else {
                $strSQLFoundRows = 'select found_rows() as found_rows';
                $arrFoundRows = $this->objDB->query($strSQLFoundRows);
                if (is_array($arrFoundRows) && isset($arrFoundRows[0]['found_rows'])) {
                    $intFoundRows = $arrFoundRows[0]['found_rows'];
                } else {
                    $intFoundRows = 0;
                }
                return $arrDBRet;
            }
        }
        throw new Exception('objDB in Dao_Base is not an object', self::ERRORNO_DB_GENERAL_ERROR);
    }
    
    /**
     * 做sql 转义
     */
    protected function escapeString($str) {
        if ($this->objDB) {
            $strRet = $this->objDB->escapeString($str);
            return $strRet;
        }
        throw new Exception('objDB in Dao_Base is not an object', self::ERRORNO_DB_GENERAL_ERROR);
    }
    
    /**
     * 有时用多行拼出一个sql 语句。
     * 将sql 中的"\n", "\r" 删除掉。
     */
    protected function sqlBeautifier($strSQL) {
        $arrReplace = array(
            "\n" => '',
            "\r" => '',
        );
        foreach ($arrReplace as $k => $v) {
            $strSQL = str_replace('' . $k, '' . $v, $strSQL);
        }
        $strSQL = trim($strSQL);
        return $strSQL;
    }

	public function startTransaction() {
        $this->objDB->autoCommit(false);
        $this->objDB->startTransaction();
    }   
    
    public function commit() {
        $this->objDB->commit();
    }   
    
    public function rollback() {
        $this->objDB->rollback();
    }   	
}

/* vim: set ft=php expandtab ts=4 sw=4 sts=4 tw=0: */
