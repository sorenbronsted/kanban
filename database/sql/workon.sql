set @start='2016-10-12';
set @end='2016-10-18';
select p.name, t.title, w.hours
from project p
join task t on p. uid = t.project_uid
join (
	select task_uid, start, end, sum(round((UNIX_TIMESTAMP(end) - UNIX_TIMESTAMP(start))/(60*60), 1)) as hours 
	from work 
	where start between @start and @end
	or end between @start and @end
	group by task_uid
) w on t.uid = w.task_uid
order by 1,2;

