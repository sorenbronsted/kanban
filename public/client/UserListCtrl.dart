
part of kanban;

class UserListCtrl extends BaseListCtrl {
  
  UserListCtrl() : super(new UserListView(), 'User');

  populateView(BaseListView view, String urlPrefix) {
    Rest.instance.get('rest/User').then((data) {
      view.populate(data, urlPrefix);
    });
  }

}