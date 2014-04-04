part of kanban;

class TaskListCtrl extends BaseListCtrl {
  String _currenTaskState;
  
  TaskListCtrl(EventBus eventBus) : super(eventBus, new TaskListView(), 'Task') {
    view.addHandler('taskstate_uid', _getTasks);
    view.addHandler('changeState', _changeState);
    _currenTaskState = "${TaskState.READY}";
  }
  
  populateView(TaskListView view, String urlPrefix) {
    var urlElements = Address.instance.getHashUrlElements();
    assert(urlElements != null);
    if (urlElements[0] == "Project") {
      Rest.instance.get('rest/Project/${urlElements[1]}').then((data) {
        view.legend = "Opgaver for ${data['name']}";
      });
      view.hideStateSelector();
      _getTasks("local");
    }
    else {
      view.showStateSelector();
      view.legend = "Opgaver";
      Rest.instance.get('rest/TaskState').then((data) {
        view.setStates(data);
        _getTasks("local");
      });
    }
  }
  
  void _getTasks(String data) {
    var taskView = (view as TaskListView);
    if (Address.instance.current.contains('Project')) {
      taskView.showNewButton();
      var uri = Address.instance.current.split('#');
      var parts = uri[1].split('/');
      Rest.instance.get('rest/Task?method=getByProjectUid&uid=${parts[1]}').then((data) {
        view.populate(data, "");
      });
    }
    else {
      taskView.hideNewButton();
      if (data == "local") { // this method is called from this class
        taskView.taskStateUid = _currenTaskState;
      }
      else { // this method is called from selector
        _currenTaskState = taskView.taskStateUid; // save for future reference
      }
      Rest.instance.get('rest/Task?method=getByState&uid=${_currenTaskState}').then((data) {
        view.populate(data, "");
      });
    }
  }
  
  void _changeState(String href) {
    var url = href.split("#");
    var parts = url[1].split("/");
    if (parts.length >= 4) {
      var url = "/rest/Task/${parts[1]}?method=setState&stateUid=${parts[3]}";
      Rest.instance.get(url).then((result) => _getTasks("local"));
    }
  }
}