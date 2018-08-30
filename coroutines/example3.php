<?php

/*
 * 协程
 */

//协程的支持是在迭代生成器的基础上, 增加了可以回送数据给生成器的功能(调用者发送数据给被调用的生成器函数). 这就把生成器到调用者的单向通信转变为两者之间的双向通信.

//传递数据的功能是通过迭代器的send()方法实现的. 下面的logger()协程是这种通信如何运行的例子：


function logger()
{
    while (true) {
//        fwrite($fileHandle, yield . "\n");
        echo(yield . "\n");
    }
}

//$logger = logger();
//$logger->send('Foo');
//$logger->send('Bar');

//正如你能看到,这儿yield没有作为一个语句来使用, 而是用作一个表达式, 即它能被演化成一个值. 这个值就是调用者传递给send()方法的值. 在这个例子里, yield表达式将首先被”Foo”替代输出, 然后被”Bar”替代输出.


//上面的例子里演示了yield作为接受者, 接下来我们看如何同时进行接收和发送的例子：

function gen()
{
//    $ret = (yield 'yield1');
    $ret = yield 'yield1';
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

$gen = gen();
var_dump($gen->current());    // string(6) "yield1"
var_dump($gen->send('ret1')); // string(4) "ret1"   (the first var_dump in gen)     // string(6) "yield2" (the var_dump of the ->send() return value)
var_dump($gen->send('ret2')); // string(4) "ret2"   (again from within gen)     // NULL               (the return value of ->send())

//要很快的理解输出的精确顺序可能稍微有点困难, 但你确定要搞清楚为什按照这种方式输出. 以便后续继续阅读.

//第一点,yield表达式两边的括号在PHP7以前不是可选的, 也就是说在PHP5.5和PHP5.6中圆括号是必须的.

//第二点,你可能已经注意到调用current()之前没有调用rewind().这是因为生成迭代对象的时候已经隐含地执行了rewind操作.




