
part of kanban;

class TabCtrl implements EventBusListener {
  var _view;
  
  TabCtrl() {
    _view = new TabView();
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

  @override
  void register(EventBus eventBus) {
    eventBus.listenOn(Address.eventAddressChanged, _display);
  }
}
