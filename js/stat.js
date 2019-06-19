var jourPrensent = document.getElementById("jourPresent");
var nbrJours = jourPrensent.getAttribute("class");

am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_dark);

var chart = am4core.create("chartdiv", am4charts.XYChart);


chart.paddingRight = 20;

var data = [];
for (var i = 1; i <= nbrJours; i++) {
    //------la date----////
    var divDate = document.getElementById("jour" + i);
    var laDate = divDate.getAttribute("class");
    var lAnne = laDate.slice(0, 4);
    var leMois = laDate.slice(5, 7) - 1;
    var leJour = laDate.slice(8, 10);
    //------la date----////

    //------arrivee----////
    var divArrive = document.getElementById("arrivee" + i);
    var heureArrivee = divArrive.getAttribute("class");
    var d = new Date("2019-06-01T" + heureArrivee + ":00");
    var h = d.getHours();
    var m = d.getMinutes();
    var hArrive = h * 3600 + m * 60;
    //------arrivee----////

    //------depart----////
    var divArrive = document.getElementById("depart" + i);
    var heureDepart = divArrive.getAttribute("class");
    var d = new Date("2019-06-01T" + heureDepart + ":00");
    var h = d.getHours();
    var m = d.getMinutes();
    var hDepart = h * 3600 + m * 60;
    //------depart----////

    data.push({ date: new Date(lAnne, leMois, leJour), arrive: hArrive, depart: hDepart }); //c est un tableau don date et arrive sont des clefs
}

chart.data = data;

var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0;

var valueAxis = chart.yAxes.push(new am4charts.DurationAxis());
valueAxis.tooltip.disabled = true;
valueAxis.renderer.minWidth = 35;
valueAxis.baseUnit = "second";

var series = chart.series.push(new am4charts.StepLineSeries());
series.dataFields.dateX = "date";
series.dataFields.valueY = "arrive";
series.tooltipText = "{valueY.formatDuration()}";
series.fillOpacity = 0.3;

series.name = "Series 1";
//////////////////////-depart-/////////////////////
var valueAxis2 = chart.yAxes.push(new am4charts.DurationAxis());
valueAxis2.tooltip.disabled = true;
valueAxis2.renderer.minWidth = 35;
valueAxis2.baseUnit = "second";

var series2 = chart.series.push(new am4charts.StepLineSeries());
series2.dataFields.dateX = "date";
series2.dataFields.valueY = "depart";
series2.tooltipText = "{valueY.formatDuration()}";
series2.fillOpacity = 0;
series.name = "Series 2";

/////////////////////////////////////////////

chart.cursor = new am4charts.XYCursor();

var scrollbarX = new am4charts.XYChartScrollbar();
scrollbarX.series.push(series);
chart.scrollbarX = scrollbarX;