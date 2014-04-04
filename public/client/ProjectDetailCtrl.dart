part of kanban;

class ProjectDetailCtrl extends BaseDetailCtrl {
  
  ProjectDetailCtrl(EventBus eventBus) : super(eventBus, new ProjectDetailView(), "Project") {
    view.addHandler('addUser', addUser);
    view.addHandler('delete', removeUser);
  }

  List<Future> loadTypes(ProjectDetailView view) {
    var parts = Address.instance.getHashUrlElements();
    var uid = parts.last;
    List<Future> result = new List();
    Future f1 = Rest.instance.get('/rest/User').then((data)  => view.setAllUsers(data));
    result.add(f1);
    if (uid != 'new') {
      view.showUsers();
      Future f2 = Rest.instance.get('/rest/Project/${uid}?method=getUsers').then((data)  => view.setUsers(data, "#Project/${uid}"));
      result.add(f2);
    }
    else {
      view.hideUsers();
    }
    return result;
  }
  
  void addUser(String data) {
    ProjectDetailView projectView = (view as ProjectDetailView);
    var userUid = projectView.getSelectedUser();
    var parts = Address.instance.getHashUrlElements();
    Rest.instance.get('/rest/Project/${parts[1]}?method=addUser&uid=${userUid}').then((result) {
      Rest.instance.get('/rest/Project/${parts[1]}?method=getUsers').then((data)  => projectView.setUsers(data, ""));
    });
  }
  
  void removeUser(String data) {
    ProjectDetailView projectView = (view as ProjectDetailView);
    var uri = data.split('#');
    var parts = uri[1].split('/');
    var projectUid = projectView.getInputValue("uid");
    Rest.instance.delete('/rest/UserProject/${parts[1]}').then((_) {
      Rest.instance.get('/rest/Project/${projectUid}?method=getUsers').then((data)  => projectView.setUsers(data, ""));
    });
  }
}