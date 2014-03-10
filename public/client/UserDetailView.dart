part of kanban;

class UserDetailView extends BaseDetailView {
  void setAllProjects(List data) => UiHelper.populateSelect('project_uid', data, (Map elem) => elem['name']);
  void setProjects(List data, String urlPrefix) {
    UiHelper.populateTable('#projects', data, "#UserProject");
    onLinkClick("#projects tbody");
  }
  
  void onLoad() {
    super.onLoad();
    onClick("input[name=addProject]", false);
  }
  
  String getSelectedProject() => getSelectValue('project_uid');
}