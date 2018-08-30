<?php
/**
 * Created by PhpStorm.
 * User: maduo
 * Date: 2018/8/24
 * Time: 下午2:22
 */

class CrossRiver
{
    public $sheep;  //妖怪

    public $wolves;       //僧侣

    public $crossed_wolves;

    public $crossed_sheep;

    public $boat_sheep;

    public $boat_wolves;

    public $plan_cross;

    public $plan_back;

    public $capacity;

    public $message;

    public $prev_sheep;

    public $prev_wolves;

    public $step;

    public function __construct($sheep, $wolves)
    {
        $this->wolves = $wolves;
        $this->sheep = $sheep;
        $this->crossed_wolves = $this->crossed_sheep = 0;
        $this->boat_sheep = $this->boat_wolves = 0;
        $this->capacity = 2;
        $this->step = [];
        $this->prev_sheep = $this->prev_wolves = 0;
        $this->eat();
    }

    public function cross_get_in()
    {
        $sheep = $this->sheep;
        $wolves = $this->wolves;
        $this->sheep -= $this->boat_sheep;
        $this->wolves -= $this->boat_wolves;
        if ($this->boat()) {
            return true;
        } else {
            $this->sheep = $sheep;
            $this->wolves = $wolves;
            return false;
        }
    }

    public function cross_get_off()
    {
        $sheep = $this->crossed_sheep;
        $wolves = $this->crossed_wolves;
        $this->crossed_sheep += $this->boat_sheep;
        $this->crossed_wolves += $this->boat_wolves;
        $this->boat_sheep = $this->boat_wolves = 0;
        if ($this->eat()) {
            return true;
        } else {
            $this->crossed_sheep = $sheep;
            $this->crossed_wolves = $wolves;
            return false;
        }
    }

    public function back_get_in()
    {
        $sheep = $this->crossed_sheep;
        $wolves = $this->crossed_wolves;
        $this->crossed_sheep -= $this->boat_sheep;
        $this->crossed_wolves -= $this->boat_wolves;
        if ($this->boat()) {
            return true;
        } else {
            $this->crossed_sheep = $sheep;
            $this->crossed_wolves = $wolves;
            return false;
        }
    }

    public function back_get_off()
    {
        $sheep = $this->sheep;
        $wolves = $this->wolves;
        $this->sheep += $this->boat_sheep;
        $this->wolves += $this->boat_wolves;
        $this->boat_sheep = $this->boat_wolves = 0;
        if ($this->eat()) {
            return true;
        } else {
            $this->sheep = $sheep;
            $this->wolves = $wolves;
            return false;
        }
    }

    public function boat()
    {
        if ($this->boat_wolves + $this->boat_sheep > 2) {
            $this->message = 'more animals!';
            return false;
            die('more animals!');
        }

        if ($this->boat_wolves + $this->boat_sheep < 1) {
            $this->message = 'can\'t work!';
            return false;
            die("can't work!");
        }

        return $this->eat();
    }

    public function eat()
    {
        if ($this->sheep > 0 && $this->sheep < $this->wolves) {
            $this->message = 'fail, more wolves on shore!';
            return false;
            die('fail, more wolves on shore!');
        }

        if ($this->boat_sheep > 0 && $this->boat_sheep < $this->boat_wolves) {
            $this->message = 'fail, more wolves on boat!';
            return false;
            die('fail, more wolves on boat!');
        }

        if ($this->crossed_sheep > 0 && $this->crossed_sheep < $this->crossed_wolves) {
            $this->message = 'fail, more wolves on other other shore!';
            return false;
            die('fail, more wolves on other other shore!');
        }

        return true;
    }

    public function get_plan_cross()
    {
        $num = $this->wolves + $this->sheep;
        $this->plan_cross = $num * ($num + 1) / 2;
    }

    public function action_cross()
    {
        if (count($this->step) > 10) {
//            return $this->step;
//            echo json_encode($this->step);
            echo count($this->step);
            exit;
        }

//        $return = [];
        $step = [];
        if ($this->sheep) {
            for ($i = 0; $i <= $this->capacity; $i++) {
                $this->boat_sheep = $i;
                for ($j = 0; $j <= $this->capacity - $i; $j++) {
                    $this->boat_wolves = $j;

                    if ($this->boat_sheep == $this->prev_sheep && $this->boat_wolves == $this->prev_wolves) {
                        continue;
                    }
                    $result = $this->cross_get_in();
//                    $return[] = [
//                        'boat_sheep' => $this->boat_sheep,
//                        'boat_wolves' => $this->boat_wolves,
//                        'message' => $this->message
//                    ];
//                    $this->message = '';
//                    $key = 'cross_' . ($i + 10 * $j);
                    if ($result) {
                        $this->prev_sheep = $this->boat_sheep;
                        $this->prev_wolves = $this->boat_wolves;

                        $data = $this->cur_info();
                        $step['cross_get_in'] = $data;
//                        $return['plan'][$key]['get_in'] = $data;
                        $result = $this->cross_get_off();
                        if ($result) {
                            $data = $this->cur_info();
//                            $return['plan'][$key]['get_off'] = $data;
                            $step['cross_get_off'] = $data;

                            $this->step[] = $step;
//                            return $this->step;
//                            echo count($this->step);
//                            echo '<br>';

//                            exit(json_encode($this->step));

                            if ($this->sheep > 0 && $this->wolves > 0) {
                                $this->step++;
                                $this->action_back();
                            } else {
                                echo json_encode($this->step);
                                exit;
                            }
                        } else {
                            exit(json_encode($this->step));
                            $step = [];
                            $this->step = [];
                            $this->prev_sheep = $this->prev_wolves = 0;
                        }
                    } else {
                        exit(json_encode($this->step));
                        $step = [];
                        $this->step = [];
                        $this->prev_sheep = $this->prev_wolves = 0;
                    }
                }
            }
        }
//        return $return;
    }

    public function action_back()
    {
        $return = [];
        $step = [];
        if ($this->sheep) {
            for ($i = 0; $i <= $this->capacity; $i++) {
                $this->boat_sheep = $i;
                for ($j = 0; $j <= $this->capacity - $i; $j++) {
                    $this->boat_wolves = $j;

                    if ($this->boat_sheep == $this->prev_sheep && $this->boat_wolves == $this->prev_wolves) {
                        continue;
                    }

                    $result = $this->back_get_in();
//                    $return[] = [
//                        'boat_sheep' => $this->boat_sheep,
//                        'boat_wolves' => $this->boat_wolves,
//                        'message' => $this->message
//                    ];
                    $this->message = '';
//                    $key = 'back_' . ($i + 10 * $j);
                    if ($result) {
                        $this->prev_sheep = $this->boat_sheep;
                        $this->prev_wolves = $this->boat_wolves;

                        $data = $this->cur_info();
                        $step['back_get_in'] = $data;
//                        $return['plan'][$key]['get_in'] = $data;
                        $result = $this->back_get_off();

                        if ($result) {
                            $data = $this->cur_info();
                            $step['back_get_off'] = $data;
//                            $return['plan'][$key]['get_off'] = $data;

                            $this->step[] = $step;

                            if ($this->sheep > 0 && $this->wolves > 0) {
                                $this->step++;
                                $this->action_cross();
                            } else {
                                echo json_encode($this->step);
                                exit;
                            }
                        } else {
                            exit(json_encode($this->step));
                            $step = [];
                            $this->step = [];
                            $this->prev_sheep = $this->prev_wolves = 0;
                        }
                    } else {
                        echo 2313;
                        exit(json_encode($this->step));
                        $step = [];
                        $this->step = [];
                        $this->prev_sheep = $this->prev_wolves = 0;
                    }
                }
            }
        }
//        return $return;
    }

    public function cur_info()
    {
        return [
            'sheep' => $this->sheep,
            'wolves' => $this->wolves,
            'boat_sheep' => $this->boat_sheep,
            'boat_wolves' => $this->boat_wolves,
            'cross_sheep' => $this->crossed_sheep,
            'crossed_wolves' => $this->crossed_wolves,
        ];
    }
}

$model = new CrossRiver(3, 3);
$result = $model->action_cross();
//$step[] = $result;
//$result = $model->action_back();
//$step[] = $result;
echo json_encode($result);

