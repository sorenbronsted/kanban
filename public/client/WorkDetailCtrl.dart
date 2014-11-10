part of kanban;

class WorkDetailCtrl extends BaseDetailCtrl {
  
  WorkDetailCtrl(EventBus eventBus) : super(eventBus, new WorkDetailView(), "Work") {}

  List<Future> loadTypes(WorkDetailView view) {
    var parts = Address.instance.getHashUrlElements();
    List<Future> result = new List();
    if (parts.length > 3) {
      view.taskUid = parts[3];
    }
    Future f2 = Rest.instance.get('/rest/User').then((data)  => view.setUsers(data));
    result.add(f2);
    return result;
  }
}