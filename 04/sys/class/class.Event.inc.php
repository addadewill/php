<?php

/**
 * Stores event information
 *保存活动信息
 * PHP version 5
 *
 * _loadEventData()方法的原始输出不能立刻用于日历页面，需要对它返回的数据进行加工，按照活动发生日期进行索引分组，为了便于引用，对活动字段进行简化
 * 最终目标是得到一个由活动对象构成的数组。这个数组以月份中的日期为索引，保存日期对应的每个活动对象。
 *
 * @author     Jason Lengstorf <jason.lengstorf@ennuidesign.com>
 * @copyright  2010 Ennui Design
 * @license    http://www.opensource.org/licenses/mit-license.html
 */
class Event
{

    /**
     * The event ID
     *活动ＩＤ
     * @var int
     */
    public $id;

    /**
     * The event title
     *活动标题
     * @var string
     */
    public $title;

    /**
     * The event description
     *活动的描述
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
     *接受一个活动数组并储存该数据
　　　　　*活动有５个公开的属性，构造函数负责使用数据库查询返回的关联数组设定的这些属性。
     * @param array $event Associative array of event data
     * @return void
　　　　　*_loadEventData返回的数组就是构造函数的参数。
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
