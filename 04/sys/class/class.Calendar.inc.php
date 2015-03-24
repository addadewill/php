<?php
  /**
		*
		*/
class Calendar extends DB_Connect {
  //
	/*
	 *日历根据此日期构建
	 *日历显示日期，格式为YYYY-MM-DD HH:MM:SS
	 */
	private $_useDate;
	//

	private $_m;
	//日历显示月份

	private $_y;
	//当前显示月份是哪一年

	private $_daysInMonth;
	//这个月有多少天

	private $_startDay;
	//这个月的起始日周几　０～　６

	public function __construct($dbo=NULL, $useDate=NULL){
	//调用父类构造函数，检查数据库对象
		parent::__construct($dbo);

  //$useDate生成日历使用的日期
		if (isset($useDate) ){
		  $this->_useDate = $useDate;
		}
		else {
		  $this->_useDate = date('Y-m-d H:i:s');
		}
  //把日期转换成时间戳，确定日历要显示的年和月
		$ts = strtotime ($this->_useDate);
		$this->_m = date('m', $ts);
		$this->_y = date('Y', $ts);

    //确定这个月有多少天
       $this->_daysInMonth = cal_days_in_month( 
                                   CAL_GREGORIAN, 
                                   $this->_m, 
                                   $this->_y);
   //确定这个月从周几开始
    $ts = mktime(0, 0, 0, $this->_m, 1, $this->_y);
    $this->_startDay = date('w', $ts);

	}

	/**
         *此方法用于获取活动数据，就是访问数据库并得到这些数据
　　　　　　　　　*要么得到一个特定的活动，要么得到一个月所有的活动
	 *将活动信息载入一个数组
	 *$id 用来过滤结果的可选活动ID
	 *
	 */
	private function __loadEventDate($id=NULL){
    $sql="SELECT `event_id`,`event_title`,`event_desc`,`event_start`,`event_end` FROM `events`"; 	

		//如果提供了活动ＩＤ，则添加一个where 子句只返回该活动
		if ( !empty($id) ){ 
		  $sql .= "WHERE `event_id` = :id LIMIT 1" ; 
		}
		//要不然就载入该月所有活动
		else{
			//計算一個月第一天 0時0分0秒 的時間戳 
	     $start_ts=mktime(0,0,0,$this->_m,1,$this->_y);
      //計算一個月最後一天 23時59分59秒 的時間戳 
			 $end_ts=mktime(23,59,59,$this->_m+1,0,$this->_y);
      //这样就得到了可以操作日期格式
			 $start_date=date('Y-m-d H:i:s',$start_ts);
			 $end_date=date('Y-m-d H:i:s', $end_ts); 

		  //找出当月所有活动
			 $sql .= "WHERE `event_start` BETWEEN '$start_date' AND '$end_date' ORDER BY `event_start`"; 
		}
		//经过以上步骤，终于得到理想SQL
		try{
		  $stmt = $this->db->prepare($sql);

			//如果ＩＤ有效则绑定此参数
			if ( !empty($id) ){
			  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
			}

			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();

			return $results;
		}
		catch( Exception $e){
		  die ($e->getMessage());
		}

	}

  /**
     * Loads all events for the month into an array
     *载入该月全部活动到一个数组
     * @return array events info
     */
    private function _createEventObj()
    {
        /*
         * Load the events array
         *载入活动数组
         */
        $arr = $this->_loadEventData();

        /*
         * Create a new array, then organize the events
         * by the day of the month　on which they occur
　　　　　　　  *按照活动发生在该月第几天，将活动重新组织到一个新数组中
         */
        $events = array();
        foreach ( $arr as $event )
        {
            $day = date('j', strtotime($event['event_start']));

            try
            {
                $events[$day][] = new Event($event);
            }
            catch ( Exception $e )
            {
                die ( $e->getMessage() );
            }
        }
        return $events;
    }

　　/**
     * Returns HTML markup to display the calendar and events
     *生成用于显示日历和活动的HTML标记
     * Using the information stored in class properties, the
     * events for the given month are loaded, the calendar is
     * generated, and the whole thing is returned as valid markup.
     *
     * @return string the calendar HTML markup
     */
    public function buildCalendar()
    {
        /*
         * Determine the calendar month and create an array of
　　　　　　　　 *确定日历显示的月份，并创建一个用于标识日历每列星期几的缩写数组
         * weekday abbreviations to label the calendar columns
         */
        $cal_month = date('F Y', strtotime($this->_useDate));
        $weekdays = array('Sun', 'Mon', 'Tue',
                'Wed', 'Thu', 'Fri', 'Sat');

        /*
         * Add a header to the calendar markup　给日历加一个标题
         */
        $html = "\n\t<h2>$cal_month</h2>";
        for ( $d=0, $labels=NULL; $d<7; ++$d )
        {
            $labels .= "\n\t\t<li>" . $weekdays[$d] . "</li>";
        }
        $html .= "\n\t<ul class=\"weekdays\">"
            . $labels . "\n\t</ul>";

        /*
         * Load events data
         */
        $events = $this->_createEventObj();

        /*
         * Create the calendar markup
         */
        $html .= "\n\t<ul>"; // Start a new unordered list
        for ( $i=1, $c=1, $t=date('j'), $m=date('m'), $y=date('Y');
                $c<=$this->_daysInMonth; ++$i )
        {
            /*
             * Apply a "fill" class to the boxes occurring before
             * the first of the month
             */
            $class = $i<=$this->_startDay ? "fill" : NULL;

            /*
             * Add a "today" class if the current date matches
             * the current date
             */
            if ( $c==$t && $m==$this->_m && $y==$this->_y )
            {
                $class = "today";
            }

            /*
             * Build the opening and closing list item tags
             */
            $ls = sprintf("\n\t\t<li class=\"%s\">", $class);
            $le = "\n\t\t</li>";

            /*
             * Add the day of the month to identify the calendar box
             */
            if ( $this->_startDay<$i && $this->_daysInMonth>=$c)
            {
                /*
                 * Format events data
                 */
                $event_info = NULL; // clear the variable
                if ( isset($events[$c]) )
                {
                    foreach ( $events[$c] as $event )
                    {
                        $link = '<a href="view.php?event_id='
                                . $event->id . '">' . $event->title
                                . '</a>';
                        $event_info .= "\n\t\t\t$link";
                    }
                }

                $date = sprintf("\n\t\t\t<strong>%02d</strong>",$c++);
            }
            else { $date="&nbsp;"; }

            /*
             * If the current day is a Saturday, wrap to the next row
             */
            $wrap = $i!=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>" : NULL;

            /*
             * Assemble the pieces into a finished item
             */
            $html .= $ls . $date . $event_info . $le . $wrap;
        }

        /*
         * Add filler to finish out the last week
         */
        while ( $i%7!=1 )
        {
            $html .= "\n\t\t<li class=\"fill\">&nbsp;</li>";
            ++$i;
        }

        /*
         * Close the final unordered list
         */
        $html .= "\n\t</ul>\n\n";

        /*
         * Return the markup for output
         */
        return $html;
    }

}
?>
