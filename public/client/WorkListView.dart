
part of kanban;

class WorkListView extends BaseListView {
  set legend(String text) {
    LegendElement legend = querySelector('#list legend');
    legend.children.clear();
    legend.appendText(text);
  }
  
  void disableNew() {
    querySelector("#functions").hidden = true;
  }
}
