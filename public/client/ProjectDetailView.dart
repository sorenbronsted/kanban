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

  void hideUsers() {
    querySelector("#users").hidden = true;
  }
  
  void showUsers() {
    querySelector("#users").hidden = false;
  }
  
  String getSelectedUser() => getSelectValue('user_uid');
}