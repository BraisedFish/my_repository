<?php

/*
 * 迭代生成器
 */

//(迭代)生成器也是一个函数,不同的是这个函数的返回值是依次返回,而不是只返回一个单独的值.或者,换句话说,生成器使你能更方便的实现了迭代器接口.下面通过实现一个xrange函数来简单说明：

function xrange($start, $end, $step = 1)
{
    for ($i = $start; $i <= $end; $i += $step) {
        yield $i;
    }
}

//foreach (xrange(0, 10, 2) as $num) {
//    echo $num, "\n";
//}


//上面这个xrange()函数提供了和PHP的内建函数range()一样的功能.但是不同的是range()函数返回的是一个包含值从1到100万0的数组(注：请查看手册). 而xrange()函数返回的是依次输出这些值的一个迭代器, 而不会真正以数组形式返回.
//这种方法的优点是显而易见的.它可以让你在处理大数据集合的时候不用一次性的加载到内存中.甚至你可以处理无限大的数据流

/*
 * 生成器为可中断的函数
 */

//要从生成器认识协程, 理解它内部是如何工作是非常重要的: 生成器是一种可中断的函数, 在它里面的yield构成了中断点.

//还是看上面的例子, 调用xrange(1,1000000)的时候, xrange()函数里代码其实并没有真正地运行. 它只是返回了一个迭代器：

$range = xrange(1, 10, 2);
var_dump($range); // object(Generator)#1
var_dump($range instanceof Iterator); // bool(true)

//这也解释了为什么xrange叫做迭代生成器, 因为它返回一个迭代器, 而这个迭代器实现了Iterator接口.

//调用迭代器的方法一次, 其中的代码运行一次.例如, 如果你调用$range->rewind(), 那么xrange()里的代码就会运行到控制流第一次出现yield的地方. 而函数内传递给yield语句的返回值可以通过$range->current()获取.

//为了继续执行生成器中yield后的代码, 你就需要调用$range->next()方法. 这将再次启动生成器, 直到下一次yield语句出现. 因此,连续调用next()和current()方法, 你就能从生成器里获得所有的值, 直到再没有yield语句出现.

//对xrange()来说, 这种情形出现在$i超过$end时. 在这中情况下, 控制流将到达函数的终点,因此将不执行任何代码.一旦这种情况发生,vaild()方法将返回假, 这时迭代结束.

