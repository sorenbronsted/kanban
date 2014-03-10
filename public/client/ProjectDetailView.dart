part of kanban;

class ProjectDetailView extends BaseDetailView {
  
  void setAllUsers(List data) => UiHelper.populateSelect('user_uid', data, (Map elem) => elem['name']);
  
  void setUsers(List data, String urlPrefix) {
    UiHelper.populateTable('#users', data, "#UserProject");
    onLinkClick("#users tbody");
  }
  
  void onLoad() {
    super.onLoad();
    onClick("input[name=addUser]", false);
  }
  
  String getSelectedUser() => getSelectValue('user_uid');
}