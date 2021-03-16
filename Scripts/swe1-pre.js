
function validationChecks(){
	if(window == window.top){
		//uncomment the line below to redirect the page.
		//window.location = "http://swe.ssa.esa.int";
	}														//end if
}
/*
function writeFooter(){
    //var text = "<p>This web page forms part of the ESA Space Situational Awareness Programme's network of space weather service development activities, and is supported under ESA contract number <CONTRACT NUMBER>.<br/>For further product-related information or enquiries contact helpdesk. E-mail: <a href=\"mailto:helpdesk.swe@ssa.esa.int\" target=\"_top\">helpdesk.swe@ssa.esa.int</a><br/>All publications and presentations using data obtained from this site should acknowledge <LIST OF CONTRIBUTING EXPERT GROUPS> and The ESA Space Situational Awareness Programme.<br/>For further information about space weather in the ESA Space Situational Awareness Programme see: <a href=\"www.esa.int/spaceweather\" target=\"_top\">www.esa.int/spaceweather</a> Access the SSA-SWE portal here: <a href=\"swe.ssa.esa.int\" target=\"_top\">swe.ssa.esa.int</a></p><p><DATAPOLICYSPECIFICS></p></div>";
    var text = "<p>This web page forms part of the ESA Space Situational Awareness Programme's network of space weather service development activities, and is supported under ESA contract number <CONTRACT NUMBER>.</p><p>For further product-related information or enquiries contact helpdesk. E-mail: <a href=\"mailto:helpdesk.swe@ssa.esa.int\" target=\"_top\">helpdesk.swe@ssa.esa.int</a></p><p>All publications and presentations using data obtained from this site should acknowledge <LIST OF CONTRIBUTING EXPERT GROUPS> and The ESA Space Situational Awareness Programme.</p><p>For further information about space weather in the ESA Space Situational Awareness Programme see: <a href=\"www.esa.int/spaceweather\" target=\"_top\">www.esa.int/spaceweather</a> Access the SSA-SWE portal here: <a href=\"swe.ssa.esa.int\" target=\"_top\">swe.ssa.esa.int</a></p><p><DATAPOLICYSPECIFICS></p></div>";
    text = text.replace("<CONTRACT NUMBER>", contractnr).replace("<LIST OF CONTRIBUTING EXPERT GROUPS>", eglist).replace("<DATAPOLICYSPECIFICS>", datapolicyspecifics);
	document.write(text);
}
*/
function writeFooter(){ 
    var text = "<p>This web page forms part of the ESA Space Situational Awareness Programme's network of space weather service development activities, and is supported under ESA contract number <CONTRACT NUMBER>.</p><p>For further product-related information or enquiries contact helpdesk. E-mail: <a class='ack' href=\"mailto:helpdesk.swe@ssa.esa.int\" target=\"_top\">helpdesk.swe@ssa.esa.int</a></p><p>All publications and presentations using data obtained from this site should acknowledge <LIST OF CONTRIBUTING EXPERT GROUPS> and The ESA Space Situational Awareness Programme.</p><p>For further information about space weather in the ESA Space Situational Awareness Programme see: <a class='ack' href=\"http://www.esa.int/spaceweather\" target=\"_top\">www.esa.int/spaceweather</a> </p><p>Access the SSA-SWE portal here: <a class='ack' href=\"http://swe.ssa.esa.int\" target=\"_top\">swe.ssa.esa.int</a></p><br><p><DATAPOLICYSPECIFICS></p>"; 
    text = text.replace("<CONTRACT NUMBER>", contractnr).replace("<LIST OF CONTRIBUTING EXPERT GROUPS>", eglist).replace("<DATAPOLICYSPECIFICS>", datapolicyspecifics); 
    document.write(text); 
}
