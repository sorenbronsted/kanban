part of kanban;

class WorkDetailView extends BaseDetailView {
  set taskUid(String value) => setInputValue('task_uid', value);
  void setUsers(List data) => UiHelper.populateSelect('user_uid', data, (Map elem) => elem['name']);
}