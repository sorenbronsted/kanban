part of kanban;

class UserDetailCtrl extends BaseDetailCtrl {
  
  UserDetailCtrl(EventBus eventBus) : super(eventBus, new UserDetailView(), "User") {
    view.addHandler('addProject', addProject);
    view.addHandler('delete', removeProject);
  }
  
  List<Future> loadTypes(UserDetailView view) {
    var parts = Address.instance.getHashUrlElements();
    var uid = parts.last;
    List<Future> result = new List();
    Future f1 = Rest.instance.get('/rest/Project').then((data)  => view.setAllProjects(data));
    result.add(f1);
    if (uid != 'new') {
      Future f2 = Rest.instance.get('/rest/User/${uid}?method=getProjects').then((data)  => view.setProjects(data, "#User/${uid}"));
      result.add(f2);
    }
    return result;
  }
  
  void addProject(String data) {
    UserDetailView userView = (view as UserDetailView);
    var projectUid = userView.getSelectedProject();
    var parts = Address.instance.getHashUrlElements();
    Rest.instance.get('/rest/User/${parts[1]}?method=addProject&uid=${projectUid}').then((result) {
      Rest.instance.get('/rest/User/${parts[1]}?method=getProjects').then((data)  => userView.setProjects(data, ""));
    });
  }
  
  void removeProject(String data) {
    UserDetailView userView = (view as UserDetailView);
    var uri = data.split('#');
    var parts = uri[1].split('/');
    var userUid = userView.getInputValue("uid");
    Rest.instance.delete('/rest/UserProject/${parts[1]}').then((_) {
      Rest.instance.get('/rest/User/${userUid}?method=getProjects').then((data)  => userView.setProjects(data, ""));
    });
  }
}