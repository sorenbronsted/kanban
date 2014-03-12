<?php

class Status {

	public static function getReport($start, $end, $report, $time) {
		syslog(LOG_DEBUG, "start $start, end $end, report $report, time $time");
		$result = null;
		$time = strtolower($time);
		if (!in_array($time, array('week', 'month'))) {
			throw IllegalArgumentException($time, __FILE__, __LINE__);
		}
		$start = Date::parse($start);
		$end = Date::parse($end);

		switch ($report) {
			case 'userWork':
				$result = self::getUserWork($start, $end, $time);
				break;
			case 'estimateProjects':
				$result = self::getEstimateProjects($start, $end, $time);
				break;
			case 'estimateTaskStates':
				$result = self::getEstimateTaskStates($start, $end);
				break;
			case 'estimateEvaluation':
				$result = self::getEstimateEvaluation($start, $end, $time);
				break;
			default:
				throw new ApplicationException("Valgt rapport $report findes ikke");
		}
		return new DataWrapper($result);
	}

	private static function getEstimateEvaluation(Date $start, Date $end, $time) {
		$timefunc = "$time(t.created".($time == 'week' ? ',3' : '').")";
		$sql =  "select p.name, t1.title, t1.estimate, t2.used, t1.estimate - t2.used diff ".
						"from task t1 ".
						"join ( ".
						"  select t.uid, round(sum(to_seconds(end)-to_seconds(start))/3600,2) used ".
						"  from task t ".
						"  join work w on t.uid = w.task_uid ".
						"  where w.start between ? and ? ".
						"  group by t.uid ".
						") t2 on t1.uid = t2.uid ".
						"join project p on p.uid = t1.project_uid ".
						"order by p.name, diff desc ";
		$data = self::getData($sql, $start, $end);
		$result = array();
		$result[] = array_keys($data[0]);
		foreach($data as $row) {
			$result[] = array_values($row);
		}
		return $result;
	}
	
	private static function getEstimateTaskStates(Date $start, Date $end) {
		$sql =  "select ts.name state, p.name project, sum(t.estimate) estimate ".
						"from taskstate ts ".
						"left outer join task t on ts.uid = t.taskstate_uid ".
						"left outer join project p on p.uid = t.project_uid ".
						"where t.created between ? and ? ".
						"group by ts.name, p.name ".
						//"having sum(t.points) is not null ".
						"order by ts.uid, p.name";
		$data = self::getData($sql, $start, $end);
		return Math::pivot('project', 'state', 'estimate', $data);
	}
	
	private static function getEstimateProjects(Date $start, Date $end, $time) {
		$timefunc = "$time(t.created".($time == 'week' ? ',3' : '').")";
		$timeColumn = "concat(year(t.created),'-',$timefunc)";
		$sql = "select $timeColumn $time, p.name, sum(t.estimate) estimate ".
					 "from task t ".
					 "join project p on p.uid = t.project_uid ".
					 "where t.created between ? and ? ". 
					 "group by $time, name ".
					 "having sum(t.estimate) is not null ".
					 "order by year(t.created), $timefunc, p.name";
		$data = self::getData($sql, $start, $end);
		return Math::pivot($time, 'name', 'estimate', $data);
	}
	
	private static function getUserWork(Date $start, Date $end, $time) {
		$timefunc = "$time(w.start".($time == 'week' ? ',3' : '').")";
		$timeColumn = "concat(year(w.start),'-',$timefunc)";
		$sql = "select $timeColumn $time, u.name,round(sum(to_seconds(end)-to_seconds(start))/3600,2) used ".
						"from work w ".
						"join user u on u.uid = w.user_uid ".
						"where w.start between ? and ? ".
						"group by $time, name ".
						"order by year(w.start), $timefunc, u.name";
		$data = self::getData($sql, $start, $end);
		return Math::pivot($time, 'name', 'used', $data);
	}
	
	private static function getData($sql, Date $start, Date $end) {
		$cursor = Db::prepareQuery(DbObject::$db, $sql, array($start, $end));
		$data = array();
		while($cursor->hasNext()) {
			$data[] = $cursor->next();
		}
		return $data;
	}
}

?>