<?php

namespace Rx\Observable;

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Scheduler\ImmediateScheduler;
use Rx\SchedulerInterface;

class RangeObservable extends Observable
{
    /** @var integer */
    private $start;

    /** @var integer */
    private $count;

    /** @var SchedulerInterface */
    private $scheduler;

    /**
     * SkipLastOperator constructor.
     * @param $start
     * @param $count
     * @param SchedulerInterface $scheduler
     */
    public function __construct($start, $count, SchedulerInterface $scheduler = null)
    {
        if (!is_int($start) || !is_int($count)) {
            throw new \InvalidArgumentException("'start' and 'count' must be an integer");
        }

        $this->start     = $start;
        $this->count     = $count;
        $this->scheduler = $scheduler ?: new ImmediateScheduler();

    }

    public function subscribe(ObserverInterface $observer)
    {

        $i = 0;

        return $this->scheduler->scheduleRecursive(function ($reschedule) use (&$observer, &$i) {
            if ($i < $this->count) {
                $observer->onNext($this->start + $i);
                $i++;
                $reschedule();

            } else {
                $observer->onCompleted();
            }
        });
    }
}