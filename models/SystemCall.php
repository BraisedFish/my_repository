<?php
/**
 * Created by PhpStorm.
 * User: maduo
 * Date: 2018/8/27
 * Time: 下午2:38
 */
namespace vi\models;


class SystemCall
{
    protected $callback;

    public function __construct(callable $callback) {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler) {    //当SystemCall对象被当做函数调用时调用该方法
//        var_dump($task);
//        var_dump($scheduler->getTaskMap());
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}