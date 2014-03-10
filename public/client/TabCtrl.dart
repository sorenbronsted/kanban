
part of kanban;

class TabCtrl {
  var _view;
  
  TabCtrl(EventBus eventBus) {
    _view = new TabView();
    eventBus.addListener(Address.eventAddressChanged, _display);
  }

  _display(event) {
    var parts = Address.instance.getHashUrlElements();
    if (parts.last == "new") {
      return;
    }
    if (parts.length > 0) {
      _view.showTabs(parts.first);
      _view.selectTab(parts.first);
    }
  }
}
