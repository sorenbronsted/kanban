part of kanban;

class ProjectListCtrl extends BaseListCtrl {
  
  ProjectListCtrl(EventBus eventBus) : super(eventBus, new ProjectListView(), 'Project');

  populateView(BaseListView view, String urlPrefix) {
    Rest.instance.get('/rest/Project').then((data) {
      view.populate(data, urlPrefix);
    });
  }
}