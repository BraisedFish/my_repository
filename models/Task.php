<?php
/**
 * Created by PhpStorm.
 * User: maduo
 * Date: 2018/8/27
 * Time: 下午2:35
 */
namespace vi\models;

use Generator;

/**
 * Class Task
 * 一个任务就是用任务ID标记的一个协程(函数).
 * 使用setSendValue()方法, 你可以指定哪些值将被发送到下次的恢复(在之后你会了解到我们需要这个), run()函数确实没有做什么, 除了调用send()方法的协同程序, 要理解为什么添加了一个 beforeFirstYieldflag变量
 * @package vi\models
 */
class Task {
    protected $taskId;
    protected $coroutine;
    protected $sendValue = null;
    protected $beforeFirstYield = true;

    public function __construct($taskId, Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    public function getTaskId() {
        return $this->taskId;
    }

    public function setSendValue($sendValue) {
        $this->sendValue = $sendValue;
    }

    public function run() {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    public function isFinished() {
        return !$this->coroutine->valid();
    }
}