part of kanban;

class TaskDetailCtrl extends BaseDetailCtrl {
  
  TaskDetailCtrl(EventBus eventBus) : super(eventBus, new TaskDetailView(), "Task") {
    view.addHandler('addComment', addComment);
    view.addHandler('delete', removeComment);
  }
  
  List<Future> loadTypes(TaskDetailView view) {
    var parts = Address.instance.getHashUrlElements();
    if (parts.last == 'new' && parts.length > 3) {
      view.projectUid = parts[1];
    }
    List<Future> result = new List();
    Future f1 = Rest.instance.get('/rest/TaskType').then((data)  => view.setTypes(data));
    result.add(f1);
    Future f2 = Rest.instance.get('/rest/TaskState').then((data)  => view.setStates(data));
    result.add(f2);
    Future f3 = Rest.instance.get('/rest/User').then((data) {
      view.setRequester(data);
      view.setOwner(data);
    });
    result.add(f3);
    if (parts.last != 'new') {
      Future f4 = Rest.instance.get('/rest/Comment?task_uid=${parts.last}').then((data)  => view.setComments(data, "#Comment"));
      result.add(f4);
    }
    return result;
  }

  void addComment(String data) {
    TaskDetailView taskView = (view as TaskDetailView);
    var parts = Address.instance.getHashUrlElements();
    Map data = {};
    data['task_uid'] = parts.last;
    data['text'] = taskView.text;
    Rest.instance.post('/rest/Comment', data).then((result) {
      Rest.instance.get('/rest/Comment?method=getByTask&uid=${parts.last}').then((data)  => taskView.setComments(data, "#Comment"));
    });
  }

  void removeComment(String data) {
    TaskDetailView taskView = (view as TaskDetailView);
    var taskParts = Address.instance.getHashUrlElements();
    var uri = data.split('#');
    var parts = uri[1].split('/');
    Rest.instance.delete('/rest/Comment/${parts[1]}').then((result) {
      Rest.instance.get('/rest/Comment?method=getByTask&uid=${taskParts.last}').then((data)  => taskView.setComments(data, "#Comment"));
    });
  }

}