
part of kanban;

class WorkListCtrl extends BaseListCtrl {
  
  WorkListCtrl() : super(new WorkListView(), 'Work');
  
  populateView(WorkListView view, String urlPrefix) {
    var parts = Address.instance.getHashUrlElements();
    parts = parts.sublist(parts.length - 3);
    var method = '';
    if (parts.first == 'User') {
      view.disableNew();
      Rest.instance.get('/rest/User/${parts[1]}').then((data) {
        view.legend = "Timer for ${data['name']}";
      });
      method = 'getByUserUid';
    }
    else if (parts.first == 'Task'){
      Rest.instance.get('/rest/Task/${parts[1]}').then((data) {
        view.legend = "Timer for ${data['title']}";
      });
      method = 'getByTaskUid';
    }
    if (method != '') {
      Rest.instance.get('/rest/Work?method=${method}&uid=${parts[1]}').then((data) {
        view.populate(data, urlPrefix);
      });
    }
  }
}