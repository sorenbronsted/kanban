part of kanban;

class WorkDetailCtrl extends BaseDetailCtrl {
  
  WorkDetailCtrl(EventBus eventBus) : super(eventBus, new WorkDetailView(), "Work") {}

  List<Future> loadTypes(WorkDetailView view) {
    var parts = Address.instance.getHashUrlElements();
    if (parts.last == 'new') {
      view.taskUid = parts[parts.length - 3];
    }
    List<Future> result = new List();
    Future f2 = Rest.instance.get('/rest/User').then((data)  => view.setUsers(data));
    result.add(f2);
    return result;
  }
}