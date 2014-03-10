
part of kanban;

class TabView {

  TabView() {
    var url = window.location.href;
    Future f = Rest.instance.load('view/TabView.html');
    f.then((html) {
      querySelector('#tabs').setInnerHtml(html.toString(), treeSanitizer : new NullTreeSanitizer());
      //selectTab(url);
    });
  }
  
  void showTabs(String link) {
    UListElement elem = querySelector("#${link}Tabs");
    if (elem != null) {
      querySelectorAll('ul').forEach((UListElement elem) {
        elem.style.display = 'none';
      });
      elem.style.display = 'inherit';
    }
  }
  
  void selectTab(String link) {
    var selected = querySelector("#tabs a[href='#${link}']");
    if (selected != null) {
      var current = querySelector("#tabs a.selected");
      if (current != null) {
        current.classes.remove('selected');
      }
      selected.classes.add('selected');
    }
  }
}
