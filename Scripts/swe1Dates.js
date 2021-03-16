function IsLeapYear(year){
    var leaping = false;
    if(year%400 == 0){
		leaping = true;
    }
    else{
		if(year%100 == 0){
			leaping = false;
		}
		else{
			if(year%4 == 0){
				leaping = true;
			}
			else{
				leaping = false;
			}
		}
    }
    return leaping;
}

function GetDateFromString(datestring){
	//format: YYYY-MM-ddTHH:mm:ss.SSSSSSSZ
    var year = datestring.substring(0,4);
    var month = datestring.substring(5,7);
	var day = datestring.substring(8,10);
    var hour = datestring.substring(11,13);
    var minute = datestring.substring(14,16);
    var seconds = datestring.substring(17,19);
						    //month is 0-based index.
    var dts = new Date(year, month-1, day, hour, minute, seconds);
    return dts;
}

function GetDateStringFromImportPlotParamString(datestring){
	var year = datestring.substring(0,4);
	var month = datestring.substring(4,6);
	var day = datestring.substring(6,8);
    var hour = datestring.substring(8,10);
    var minute = datestring.substring(10,12);
    var seconds = datestring.substring(12,14);	
	var dts = day + "/" + month + "/" + year + " " + hour + ":" + minute + ":" + seconds;
	return dts;
}

function GetDateStringForExportPlotParamString(datestring){
	var day = datestring.substring(0,2);
	var month = datestring.substring(3,5);
	var year = datestring.substring(6,10);
    var hour = datestring.substring(11,13);
    var minute = datestring.substring(14,16);
    var seconds = datestring.substring(17,19);	
	var dts = year+month+day+hour+minute+seconds;
	return dts;
}

function GetFullStringDate(dts){
    var syear, smonth, sday, shour, sminute, sseconds;    
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var year = dts.getFullYear();
    var month = dts.getMonth();
    var day = dts.getDate();
    var hour = dts.getHours();
    var minute = dts.getMinutes();
    var seconds = dts.getSeconds();
    if(day < 10){	sday = "0" + day;	}	else{	sday = day+"";	}
    smonth = months[month];
    if(hour < 10)	{	shour = "0" + hour;			}	else{	shour = hour+"";		}
    if(minute < 10)	{	sminute = "0" + minute;		}	else{	sminute = minute+"";	}
    if(seconds < 10){	sseconds = "0" + seconds;	}	else{	sseconds = seconds+"";	}
    syear = year+"";
    return sday + " " + smonth + " " + syear + " " + shour + ":" + sminute + ":" + sseconds;
}

function GetStringDate(date, offsetmillis){
	return GetStringDate(date, offsetmillis, false);
}

function GetStringDate(date, offsetmillis, iso8601){
    var dts;
    if(date == null){
		try{
			dts = new Date();
			dts = new Date(dts.getUTCFullYear(), dts.getUTCMonth(), dts.getUTCDate(), 
					dts.getUTCHours(), dts.getUTCMinutes(), dts.getUTCSeconds(), dts.getUTCMilliseconds());
		}									//end try
		catch(ex){
			setError(ex.stack);
		}									//end catch
    }										//end if
    else{
		dts = date;
    }										//end else
    dts.setTime(dts.getTime() + offsetmillis);
    var retval = "";
	var year = dts.getFullYear();
	var month = dts.getMonth() + 1;
	var day = dts.getDate();
	var hour = dts.getHours();
	var minute = dts.getMinutes();
	var seconds = dts.getSeconds();		
	var milliseconds = dts.getMilliseconds();
	var syear, smonth, sday, shour, sminute, sseconds, smillis;
	if(day < 10)	{	sday = "0" + day;			}	else{	sday = day+"";			}
	if(month < 10)	{	smonth = "0" + month;		}	else{	smonth = month+"";		}
	if(hour < 10)	{	shour = "0" + hour;			}	else{	shour = hour+"";		}
	if(minute < 10)	{	sminute = "0" + minute;		}	else{	sminute = minute+"";	}
	if(seconds < 10){	sseconds = "0" + seconds;	}	else{	sseconds = seconds+"";	}
	if(milliseconds < 10){
		smillis = "00" + milliseconds;
	}														//end if
	else{
		if(milliseconds < 100){
			smillis = "0" + milliseconds;
		}													//end if
		else{
			smillis = milliseconds+"";
		}													//end else
	}														//end else
	syear = year+"";
	if(!iso8601){
		retval = sday + "/" + smonth + "/" + syear + " " + shour + ":" + sminute + ":" + sseconds;
    }										//end if
    else{
		//this version would doubly convert to UTC
		//retval = dts.toISOString();
		retval = syear + "-" + smonth + "-" + sday + "T" + shour + ":" + sminute + ":" + sseconds + "." + smillis + "Z";
	}
    return retval;
}

function isLargerThan(date1, date2){
	var result = false;
	if(date1 != null && date2 != null){
		result = date1.getTime() > date2.getTime();
	}														//end if
	return result;
}

function isSmallerThan(date1, date2){
	var result = false;
	if(date1 != null && date2 != null){
		result = date1.getTime() > date2.getTime();
	}														//end if
	return result;	
}

function isEqualThan(date1, date2){
	var result = false;
	if(date1 != null && date2 != null){
		result = date1.getTime() == date2.getTime();
	}														//end if
	return result;
}