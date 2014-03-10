
library kanban;

import 'dart:html';
import 'dart:async';
import 'package:json/json.dart';
import '../webui/EventBus.dart';
import '../webui/Address.dart';
import '../webui/BaseView.dart';
import '../webui/Rest.dart';
import '../webui/InputValidator.dart';
import '../webui/Formatter.dart';

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
  EventBus eventBus = new EventBus();

  eventBus.addListener("RestRequestStart", (event) {
    querySelector("body").style.cursor = "progress";
    querySelector("#loader img").hidden = false;
  });
  eventBus.addListener("RestRequestDone", (event) {
    querySelector("body").style.cursor = "auto";
    querySelector("#loader img").hidden = true;
  });

  Rest.instance.eventBus = eventBus;
  Rest.instance.errorHandler = (text) {
    window.alert(text);
  };

  var controllers = new List();
  controllers.add(new TabCtrl(eventBus));
  controllers.add(new ProjectListCtrl(eventBus));
  controllers.add(new ProjectDetailCtrl(eventBus));
  controllers.add(new TaskListCtrl(eventBus));
  controllers.add(new TaskDetailCtrl(eventBus));
  controllers.add(new UserListCtrl(eventBus));
  controllers.add(new UserDetailCtrl(eventBus));
  controllers.add(new WorkListCtrl(eventBus));
  controllers.add(new WorkDetailCtrl(eventBus));
  controllers.add(new StatusListCtrl(eventBus));
  
  Address.instance.eventBus = eventBus;
  // Check to see how we are started
  if (window.location.href.contains("#")) {
    Address.instance.goto(window.location.href);
  }
  else {
    Address.instance.goto("Project");
  }
}