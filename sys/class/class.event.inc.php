<?php

/**
 * Stores event information
 *_loadEventData()方法原始输出不能立刻用于日历页面，因为活动需要按日期显示
 *对返回数据进行加工，按活动发生日期进行索引分组，简化
 *最终目标是得到一个由活动对象构成的数组。这个数组以月份中的日期为索引，保存日期对应的每个活动对象
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT License, available
 * at http://www.opensource.org/licenses/mit-license.html
 *
 * @author     Jason Lengstorf <jason.lengstorf@ennuidesign.com>
 * @copyright  2010 Ennui Design
 * @license    http://www.opensource.org/licenses/mit-license.html
 */
class Event
{

    /**
     * The event ID
     *
     * @var int
     */
    public $id;

    /**
     * The event title
     *
     * @var string
     */
    public $title;

    /**
     * The event description
     *
     * @var string
     */
    public $description;

    /**
     * The event start time
     *
     * @var string
     */
    public $start;

    /**
     * The event end time
     *
     * @var string
     */
    public $end;

    /**
     * Accepts an array of event data and stores it
     *
     * @param array $event Associative array of event data
     * @return void 这个构造函数无返回，注定是个处理中间类
		 *每初始化一次这个类，就得到一次上面的５个公共属性值
     */
    public function __construct($event)
    {
        if ( is_array($event) )
        {
            $this->id = $event['event_id'];
            $this->title = $event['event_title'];
            $this->description = $event['event_desc'];
            $this->start = $event['event_start'];
            $this->end = $event['event_end'];
        }
        else
        {
            throw new Exception("No event data was supplied.");
        }
    }

}

?>
