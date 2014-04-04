part of kanban;

class TaskDetailView extends BaseDetailView {
  set projectUid(String value) => setInputValue('project_uid', value);
  void setTypes(List data) => UiHelper.populateSelect('tasktype_uid', data, (Map elem) => elem['name']);
  void setStates(List data) => UiHelper.populateSelect('taskstate_uid', data, (Map elem) => elem['name']);
  void setRequester(List data) => UiHelper.populateSelect('requester_uid', data, (Map elem) => elem['name']);
  void setOwner(List data) => UiHelper.populateSelect('owner_uid', data, (Map elem) => elem['name']);
  
  void onLoad() {
    super.onLoad();
    onClick("input[name=addComment]", false);
  }

  void setComments(List data, String urlPrefix) {
    UiHelper.populateTable('#comments', data, urlPrefix);
    onLinkClick("#comments tbody");
  }
  
  String get text => getInputValue('text');

  void hideComment() {
    querySelector('form[name=comment]').hidden = true;
  }
  
  void showComment() {
    querySelector('form[name=comment]').hidden = false;
  }
}