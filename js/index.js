var DivpresentDev = document.getElementById("present1");
var presentDev = DivpresentDev.getAttribute("class");

var DivabsentDev = document.getElementById("absent1");
var absentDev = DivabsentDev.getAttribute("class");

var DivpresentRef = document.getElementById("present2");
var presentRef = DivpresentRef.getAttribute("class");

var DivabsentRef = document.getElementById("absent2");
var absentRef = DivabsentRef.getAttribute("class");

var DivpresentData = document.getElementById("present3");
var presentData = DivpresentData.getAttribute("class");

var DivabsentData = document.getElementById("absent3");
var absentData = DivabsentData.getAttribute("class");


am4core.useTheme(am4themes_animated);

var chart = am4core.create("chartdiv", am4charts.XYChart);

chart.fill = am4core.color("#008e8e");
chart.stroke = am4core.color("#008e8e");
chart.bar = am4core.color("#008e8e");

chart.data = [{
    "category": "Dev Web",
    "value1": presentDev,
    "value2": absentDev
}, {
    "category": "Ref Dig",
    "value1": presentRef,
    "value2": absentRef
}, {
    "category": "Data Art",
    "value1": presentData,
    "value2": absentData
}]

chart.colors.step = 2;
chart.padding(30, 30, 10, 30);

chart.legend = new am4charts.Legend();
chart.legend.itemContainers.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;

var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.renderer.minGridDistance = 60;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.interactionsEnabled = false;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.min = 0;
valueAxis.max = 100;
valueAxis.strictMinMax = true;
valueAxis.calculateTotals = true;

valueAxis.renderer.minGridDistance = 20;
valueAxis.renderer.minWidth = 35;

var series1 = chart.series.push(new am4charts.ColumnSeries());
series1.columns.template.tooltipText = "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
series1.columns.template.column.strokeOpacity = 1;
series1.name = "Présent";
series1.dataFields.categoryX = "category";
series1.dataFields.valueY = "value1";
series1.dataFields.valueYShow = "totalPercent";
series1.dataItems.template.locations.categoryX = 0.5;
series1.stacked = true;
series1.tooltip.pointerOrientation = "vertical";
series1.tooltip.dy = -20;
series1.cursorHoverEnabled = false;

var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
bullet1.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
bullet1.locationY = 0.5;
bullet1.label.fill = am4core.color("#ffffff");
bullet1.interactionsEnabled = false;

var series2 = chart.series.push(series1.clone());
series2.name = "Absent";
series2.dataFields.valueY = "value2";
series2.fill = chart.colors.next();
series2.stroke = series2.fill;
series2.cursorHoverEnabled = false;

// var series3 = chart.series.push(series1.clone()); //pour les online
// series3.name = "Series 3";
// series3.dataFields.valueY = "value3";
// series3.fill = chart.colors.next();
// series3.stroke = series3.fill;
// series3.cursorHoverEnabled = false;

chart.scrollbarX = new am4core.Scrollbar();

chart.cursor = new am4charts.XYCursor();
chart.cursor.behavior = "panX";
