
library kanban;

import 'dart:html';
import 'dart:async';
import 'package:webui/webui.dart';

part 'TabView.dart';
part 'TabCtrl.dart';
part 'ProjectListView.dart';
part 'ProjectListCtrl.dart';
part 'ProjectDetailView.dart';
part 'ProjectDetailCtrl.dart';
part 'TaskListView.dart';
part 'TaskListCtrl.dart';
part 'TaskDetailView.dart';
part 'TaskDetailCtrl.dart';
part 'UserListView.dart';
part 'UserListCtrl.dart';
part 'UserDetailView.dart';
part 'UserDetailCtrl.dart';
part 'WorkListView.dart';
part 'WorkListCtrl.dart';
part 'WorkDetailView.dart';
part 'WorkDetailCtrl.dart';
part 'StatusListView.dart';
part 'StatusListCtrl.dart';

void main() {
  EventBus eventBus = EventBus.instance;
  eventBus.listenOn(Rest.eventRequestStart, (event) {
    querySelector("body").style.cursor = "progress";
    querySelector("#loader img").hidden = false;
  });
  eventBus.listenOn(Rest.eventRequestDone, (event) {
    querySelector("body").style.cursor = "auto";
    querySelector("#loader img").hidden = true;
  });

  Rest.instance.errorHandler = (text) {
    window.alert(text);
  };

  eventBus.register(new TabCtrl());
  eventBus.register(new ProjectListCtrl());
  eventBus.register(new ProjectDetailCtrl());
  eventBus.register(new TaskListCtrl());
  eventBus.register(new TaskDetailCtrl());
  eventBus.register(new UserListCtrl());
  eventBus.register(new UserDetailCtrl());
  eventBus.register(new WorkListCtrl());
  eventBus.register(new WorkDetailCtrl());
  eventBus.register(new StatusListCtrl());
  
  Address.instance.goto("Project");
}