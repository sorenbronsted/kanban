
part of kanban;

class TaskState {
  static const int READY = 1;
  static const int PLANNED = 2;
  static const int IMPLEMENTATION = 3;
  static const int TEST = 4;
  static const int DONE = 5;
}

class TaskListView extends BaseListView {
  String get taskStateUid => getSelectValue('taskstate_uid');
  set taskStateUid(String uid) => setSelectValue('taskstate_uid', uid);
  void setStates(List data) => UiHelper.populateSelect('taskstate_uid', data, (Map elem) => elem['name']);

  set legend(String text) {
    LegendElement legend = querySelector('#list legend');
    legend.children.clear();
    legend.appendText(text);
  }
  
  void onLoad() {
    super.onLoad();
    onChange("select[name=taskstate_uid]", false);
  }
  
  void hideStateSelector() { 
    querySelector('#stateSelector').hidden = true;
  }
  
  void showStateSelector() {
    querySelector('#stateSelector').hidden = false;
  }
  
  void hideNewButton() {
    querySelector('[name=create]').hidden = true;
  }
  
  void showNewButton() {
    querySelector('[name=create]').hidden = false;
  }
  
  void populate(List rows, String urlPrefix) {
    urlPrefix = Address.instance.getHashUrl();
    UiHelper.populateTable("#list", rows, "#${urlPrefix}", (String name, Set classes, Map row, String suggestion) {
      var value = suggestion;
      if (classes.contains('action')) {
        value = _getAction(row);
      }
      if (name == 'title' && row['tags'] != '') {
        value = '<span class="tags">${row['tags']}</span> ${suggestion}';
      }
      return value;
    });
    onLinkClick("#list tbody");
  }
  
  String _getAction(Map row) {
    var result = new StringBuffer();
    var href = "#Task/${row['uid']}/state";
    switch(row['taskstate_uid']) {
      case TaskState.READY:
        result.write("<a class='changeState' href=${href}/${TaskState.PLANNED}>add</a>"); 
        break;
      case TaskState.PLANNED:
        result.write("<a class='changeState' href=${href}/${TaskState.READY}>remove</a>");
        result.write(" | ");
        result.write("<a class='changeState' href=${href}/${TaskState.IMPLEMENTATION}>start</a>"); 
        break;
      case TaskState.IMPLEMENTATION:
        result.write("<a class='changeState' href=${href}/${TaskState.PLANNED}>stop</a>"); 
        result.write(" | ");
        result.write("<a class='changeState' href=${href}/${TaskState.TEST}>done</a>"); 
        break;
      case TaskState.TEST:
        result.write("<a class='changeState' href=${href}/${TaskState.DONE}>accept</a>"); 
        result.write(" | ");
        result.write("<a class='changeState' href=${href}/${TaskState.PLANNED}>reject</a>"); 
        break;
    }
    return result.toString();
  }
}
