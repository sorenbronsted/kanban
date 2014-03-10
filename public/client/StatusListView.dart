part of kanban;

class StatusListView extends BaseListView {

  void onLoad() {
    setValidation("input[type=text]");
    onClick("input[name=show]", true);
    onChange("select[name=report]", false);
    addHandler('report', _toggleGroupBy);
  }
  
  void _toggleGroupBy(String name) {
    Element elem = querySelector('#groupBy');
    name = getSelectValue('report');
    if (['estimateTaskStates', 'estimateEvaluation'].contains(name)) {
      elem.hidden = true;
    }
    else {
      elem.hidden = false;
    }
  }
  
  void populate(List data, String urlPrefix) {
    var time = this.getSelectValue('time');
    _makeHeader(time, data);
    _makeBody(data, time);
  }

  void _makeBody(List data, String time) {
    var header = querySelector("#list thead tr");
    var fragment = new DocumentFragment();
    var rowCount = 0;
    data.skip(1).forEach((List row) {
      var tr = new TableRowElement();
      tr.classes.add(rowCount % 2 == 0 ? "row-even" : "row-odd");
      String value = '';
      var columnCount = 0;
      row.forEach((rowValue) {
        Element e = header.children.elementAt(columnCount);
        if (e.classes.length == 0) {
          value = "${rowValue}";
        }
        else {
          value = Format.display(e.classes, "${rowValue}");
        }
        var cell = new TableCellElement();
        cell.appendText(value);
        tr.append(cell);
        columnCount++;
      });
      fragment.append(tr);
      rowCount++;
    });
    var tbody = querySelector('#list tbody');
    tbody.children.clear();
    tbody.append(fragment);
  }
  
  TableRowElement _makeHeader(String time, List data) {
    var header = new TableRowElement();
    data[0].forEach((String value) {
      var th = new Element.tag('th');
      th.appendText(value);
      //TODO type information on each column should be supplied from server
      if (data[0].contains('total')) { // if only contains 'total' it is properly a pivot tabel
        if (value != data[0].first) {
          th.classes.add('amount');
        }
      }
      else {
        if (['used', 'estimate', 'diff'].any((test) => test == value.toLowerCase())) {
          th.classes.add('amount');
        }
      }
      if (data[0].last == value) {
        th.classes.add('max');
      }
      th.id = value;
      header.append(th);
    });
    var thead = querySelector('#list thead');
    thead.children.clear();
    thead.append(header);
  }
}
 