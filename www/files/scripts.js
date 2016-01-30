function roundPlus(x, n) { //x - число, n - количество знаков
  if(isNaN(x) || isNaN(n)) return false;
  var m = Math.pow(10,n);
  return Math.round(x*m)/m;
}


function CalcDohod(percent, dohod) {
	dohod = dohod + percent;
	document.getElementById('dohodno').innerHTML = roundPlus(dohod, 4);
	setTimeout("CalcDohod("+percent+", "+dohod+")",1000);
}

function CalcTimePercent(i, lastpayment, nextpayment, t, p) {

	perc = (t - lastpayment) * 100 / (nextpayment - lastpayment);
	document.getElementById('percentline'+i).style.width = perc+'%';

	var time    = nextpayment - t;
    var hour    = parseInt(time / 3600);
    if ( hour < 1 ) hour = 0;
    time = parseInt(time - hour * 3600);
    if ( hour < 10 ) hour = '0'+hour;
 
    var minutes = parseInt(time / 60);
    if ( minutes < 1 ) minutes = 0;
    time = parseInt(time - minutes * 60);
    if ( minutes < 10 ) minutes = '0'+minutes;
    var seconds = time;
    if ( seconds < 10 ) seconds = '0'+seconds;
 
	timer = hour+':'+minutes+':'+seconds;
	document.getElementById('deptimer'+i).innerHTML = timer;

	if(timer == "00:00:00") {
		top.location.href='/deposits/';
	}

	t = t + 1;
	setTimeout("CalcTimePercent("+i+", "+lastpayment+", "+nextpayment+", "+t+", "+p+")",1000);
}