part of kanban;

class StatusListCtrl extends BaseListCtrl {
  StatusListCtrl(EventBus eventBus) : super(eventBus, new StatusListView(), 'Status') {
    view.addHandler('show', showReport);
  }
  
  void showReport(String data) {
    StatusListView statusView = (view as StatusListView);
    Map formdata = statusView.formdata;
    formdata.remove('show');
    String parameters = Rest.instance.encodeMap(formdata);
    Rest.instance.get("/rest/Status?method=getReport&${parameters}").then((data) {
      statusView.populate(data, '');
    });
  }
}