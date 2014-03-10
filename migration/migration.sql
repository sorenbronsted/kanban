delete from kanban.project;
insert into kanban.project(uid,name,created) select id,name,created_at from fulcrum.projects;

delete from kanban.user;
insert into kanban.user(uid, userid, name) select id, lower(initials), name from fulcrum.users;

delete from userproject;
insert into userproject(project_uid, user_uid) select project_id, user_id from fulcrum.projects_users;

delete from kanban.task;
insert into kanban.task(
  uid,
  title,
  description,
  points,
  estimate,
  taskstate_uid,
  tasktype_uid,
  project_uid,
  requester_uid,
  owner_uid,
  created,
  position,
  tags)
select f.id, 
  f.title, 
  f.description,
  f.estimate,
  f.estimate * 2, 
  case f.state 
  when 'accepted' then 5 
  when 'delivered' then 4 
  when 'started' then 2 
  when 'unstarted' then 2 
  when 'rejected' then 2 
  when 'finished' then 5 
  when 'unscheduled' then 1 
  end as taskstate_uid,
  case f.story_type 
  when 'feature' then 1 
  when 'bug' then 2 
  when 'release' then 3 
  when 'chore' then 3 
  end as tasktype_uid,
  f.project_id,
  f.requested_by_id,
  f.owned_by_id,
  f.created_at,
  50,
  ifnull(f.labels, '')
from fulcrum.stories f;

delete from work;
insert into work(user_uid, task_uid, start, end)
select ku.uid as user_uid, ps.story_id as task_uid, timestamp(ps.date_reported, '08:00:00') as start, 
timestampadd(MINUTE, (ps.hours*60), timestamp(ps.date_reported, '08:00:00')) as end
from projektstyring.hours ps
join kanban.user ku on ku.userid = lower(ps.user)
where ps.story_id > 0;
