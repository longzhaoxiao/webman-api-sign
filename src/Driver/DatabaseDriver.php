<?php

namespace Wengg\WebmanApiSign\Driver;

use support\Db;
use support\think\Cache;

/**
 * 数据库
 * @author bensharp <bensharp@qq.com> 2022-08-25
 */
class DatabaseDriver implements BaseDriver
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getInfo(string $app_id)
    {
        $db_cache_time = $this->config['db_cache_time'];

        $expires = is_null($db_cache_time) ? 0 : $db_cache_time;

        $cache_key = 'app_sign:' . $app_id ;
        $data = Cache::remember($cache_key,
            function () use ($app_id){
                return Db::table($this->config['table'])->where('app_id', $app_id)->first() ?: null;
            },
            $expires);
        return $data ? (array)$data : [];
    }
}
