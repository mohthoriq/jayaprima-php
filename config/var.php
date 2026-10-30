<?php
date_default_timezone_set('Asia/Jakarta');
session_start();


const HOST = 'http://localhost/jayaprima/';
const ROOTDIR = 'E:/My Project - 2025/jaya-prima/jayaprima-web-server/';
const TOKEN = 'ECWRaiq4wobZ4FfWkvUcinF4PYo6aNLfCxYvjJ714bOxBZoEozfjpmHMGlDjDsoYzO4k5HRsBLKrzqGXujXHy8scWvCXnVLCPegH';


const DBDIR = ROOTDIR . 'db/';

function scanDB(){
    $GLOBALS['dbList'] = array_diff(scandir(DBDIR, SCANDIR_SORT_DESCENDING), array('.', '..'));
}
scanDB();


function isDbYearExists(string $dbName){
    return in_array($dbName, $GLOBALS['dbList']);
}


if(isset($_GET['DBYEAR']) && isDbYearExists($_GET['DBYEAR'])){
    //load berdasarkan permintaan
    $_SESSION['DBYEAR'] = (int) $_GET['DBYEAR'];
}
if(!isset($_SESSION['DBYEAR']) || !isDbYearExists($_SESSION['DBYEAR'])){
    //load db terbaru dari yg sudah ada
    if(is_array($GLOBALS['dbList']) && sizeof($GLOBALS['dbList']) > 0)
        $_SESSION['DBYEAR'] = (int) $GLOBALS['dbList'][0];
    //db belum ada sama sekali
    else
        $_SESSION['DBYEAR'] = (int) date("Y");
}


$GLOBALS['DBCONN'] = null;
function shutdown() {
    if($GLOBALS['DBCONN'] != null) {
        @$GLOBALS['DBCONN']->close();
        unset($GLOBALS['DBCONN']);
    }
}
function isDBConnAlive(): bool {
    return @$GLOBALS['DBCONN']->exec('select sqlite_version()');
}
register_shutdown_function('shutdown');


include_once ROOTDIR . 'class/Database.php';
include_once ROOTDIR . 'class/DBUserInfo.php';
$GLOBALS['dbUserInfo'] = (new DBUserInfo())->get(1);
$GLOBALS['logoUrl'] = HOST . 'api.php?user_info=' . urlencode(json_encode(array('logo' => $GLOBALS['dbUserInfo']->id)));






