<?php
class BlockerStatus{
    public bool $isBlocked;
    public string $title;
    public string $msg;
    public string $progress;

    public function __construct($isBlocked, $title = '', $msg = '', $progress = ''){
        $this->isBlocked = $isBlocked;
        $this->title = $title;
        $this->msg = $msg;
        $this->progress = $progress;
    }
}

class WebBlocker{
    public static string $configPath = ROOTDIR . 'config/blocker';

    public static function isOnBlocked(): bool {
        $isNullDetected = false;
        do{
            if($isNullDetected) usleep(100 * 1_000);
            $ret = self::readConfig();
            $isNullDetected = $ret == null;
        } while ($isNullDetected);

        return ($ret)->isBlocked;
    }

    public static function readConfig(): ?BlockerStatus {
        $s = @unserialize(file_get_contents(self::$configPath));
        if($s === false) return null;
        return $s;
    }
    public static function writeConfig(BlockerStatus $config): bool {
        $file = fopen(self::$configPath,'w');
        $stat = fwrite($file, serialize($config));
        fclose($file);
        return is_integer($stat);
    }
}