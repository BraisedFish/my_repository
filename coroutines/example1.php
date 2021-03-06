<?php
namespace vi\coroutines;

require '../models/Scheduler.php';
require '../models/Task.php';
require '../models/SystemCall.php';

use Generator;
use vi\models\Scheduler;
use vi\models\SystemCall;
use vi\models\Task;

function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());
        $scheduler->schedule($task);
    });
}


//function task($max) {
//    $tid = (yield getTaskId()); // <-- here's the syscall!
//    for ($i = 1; $i <= $max; ++$i) {
//        echo "This is task $tid iteration $i.\n";
//        yield;
//    }
//}

function newTask(Generator $coroutine) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($coroutine) {
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

function killTask($tid) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            $task->setSendValue($scheduler->killTask($tid));
            $scheduler->schedule($task);
        }
    );
}

function childTask() {
    $tid = (yield getTaskId());
    while (true) {
        echo "Child task $tid still alive!\n";
        yield;
    }
}

function task() {
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));

    for ($i = 1; $i <= 6; ++$i) {
        echo "Parent task $tid iteration $i.\n";
        yield;

        if ($i == 3) yield killTask($childTid);
    }
}

$scheduler = new Scheduler;
$scheduler->newTask(task());
$scheduler->run();

//var_dump(getTaskId());
