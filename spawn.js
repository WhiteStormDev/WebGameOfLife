'use strict'
function spawnGameField()
{
  var htmlToPrint = "<div class=\"map\"> " +
    "<canvas id=\"game\"> </canvas>" +
    "<div class=\"filter\">" +
    "<canvas id=\"back\"> </canvas>" +
    "</div></div>"+
    "<br> " +
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"clear\" value=\"Очистить поле\">"+
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"rand\" value=\"Случайное поле\">"+
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"step\" value=\"Следующее поколение\">"+
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"autoplay\" value=\"Автовоспроизведение\">"+
    "<hr>"+
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"glider\" value=\"Глайдер\">"+
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"exploder\" value=\"Эксплодер\">"+
    "<input type=\"button\" class = \"waves-effect waves-light btn\" id=\"gosper\" value=\"Пушка Госпера\">"+
    "<script src=\"life.js\" type=\"application/javascript\"></script>";
  htmlToPrint.print();
}
