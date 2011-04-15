
/* dewak - Building Software
/* Source Copyright 2011 Dewak S.A.
/* Unauthorized reproduction is not allowed
/* ------------------------------------------------
/* $Date: 2011-04-11 18:40:04 $
/* $Author: diego $
*/

// stores the reference to the XMLHttpRequest object
var xmlHttp = createXmlHttpRequestObject(); 

// retrieves the XMLHttpRequest object
function createXmlHttpRequestObject() 
{	
  // will store the reference to the XMLHttpRequest object
  var xmlHttp;
  // if running Internet Explorer
  if(window.ActiveXObject)
  {
    try
    {
      xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch (e) 
    {
      xmlHttp = false;
    }
  }
  // if running Mozilla or other browsers
  else
  {
    try 
    {
      xmlHttp = new XMLHttpRequest();
    }
    catch (e) 
    {
      xmlHttp = false;
    }
  }
  // return the created object or display an error message
  if (!xmlHttp)
 
    alert("Error creating the XMLHttpRequest object.");
  else 
    return xmlHttp;
}

// make asynchronous HTTP request using the XMLHttpRequest object 
function showSettings()
{
	  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	  {
	    xmlHttp.open("GET", "dwk_ajax.php?request=loadSettings", true);  
	    document.getElementById("settings").style.display = "";
	    xmlHttp.onreadystatechange = handleServerResponse;
	    xmlHttp.send(null);
	  }
	  else{
	    setTimeout('showSettings()', 1000);
	  }
}

// executed automatically when a message is received from the server
function handleServerResponse() 
{
  // move forward only if the transaction has completed
  if (xmlHttp.readyState == 4) 
  {
    // status of 200 indicates the transaction completed successfully
    if (xmlHttp.status == 200) 
    {
      helloMessage = xmlHttp.responseText;
      document.getElementById ("settings").innerHTML = helloMessage;
    } 
    // a HTTP status different than 200 signals an error
    else 
    {
      alert("There was a problem accessing the server: " + xmlHttp.statusText);
    }
  }
}

// make asynchronous HTTP request using the XMLHttpRequest object 
function showWidgetStatuses()
{
	  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	  {
	    xmlHttp.open("GET", "dwk_ajax.php?request=showWidgetStatuses", true);  
	    document.getElementById("widgetStatuses").style.display = "";
	    xmlHttp.onreadystatechange = handleServerResponse_statuses;
	    xmlHttp.send(null);
	  }
	  else{
	    setTimeout('showWidgetStatuses()', 1000);
	  }
}

// executed automatically when a message is received from the server
function handleServerResponse_statuses() 
{
  // move forward only if the transaction has completed
  if (xmlHttp.readyState == 4) 
  {
    // status of 200 indicates the transaction completed successfully
    if (xmlHttp.status == 200) 
    {
    	var myData = eval( xmlHttp.responseText );
    
	  	var myChart = new JSChart('widgetStatuses', 'bar');
	  	myChart.setDataArray(myData);
	  	myChart.setBarColor('#a8b72a');
	  	myChart.setBarOpacity(0.8);
	  	myChart.setBarBorderColor('#D9EDF7');
	  	myChart.setBarValues(false);
	  	myChart.setTitleColor('#8C8383');
	  	myChart.setAxisColor('#777E81');
	  	myChart.setAxisValuesColor('#777E81');
	  
	  	var x=parseInt(windowSizeX()*0.45);
	  	var y=parseInt(windowSizeY()*0.3);
	  	
	  	myChart.setAxisNameX(' ');
	  	myChart.setAxisNameY(' ');
	  	myChart.setSize(x,y);
	  	myChart.setTitle(' ');
	  	
	  	myChart.draw();
	  	
      //document.getElementById ("widgetStatuses").innerHTML = helloMessage;
    } 
    // a HTTP status different than 200 signals an error
    else 
    {
      alert("There was a problem accessing the server: " + xmlHttp.statusText);
    }
  }
}

// make asynchronous HTTP request using the XMLHttpRequest object 
function showWidgetDepartments()
{
	  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	  {
	    xmlHttp.open("GET", "dwk_ajax.php?request=showWidgetDepartments", true);  
	    document.getElementById("widgetDepartments").style.display = "";
	    xmlHttp.onreadystatechange = handleServerResponse_departments;
	    xmlHttp.send(null);
	  }
	  else{
	    setTimeout('showWidgetDepartments()', 1000);
	  }
}

// executed automatically when a message is received from the server
function handleServerResponse_departments() 
{
  // move forward only if the transaction has completed
  if (xmlHttp.readyState == 4) 
  {
    // status of 200 indicates the transaction completed successfully
    if (xmlHttp.status == 200) 
    {
    	var myData = eval( xmlHttp.responseText );
        
	  	var myChart = new JSChart('widgetDepartments', 'bar');
	  	myChart.setDataArray(myData);
	  	myChart.setBarColor('#a8b72a');
	  	myChart.setBarOpacity(0.8);
	  	myChart.setBarBorderColor('#D9EDF7');
	  	myChart.setBarValues(true);
	  	myChart.setTitleColor('#8C8383');
	  	myChart.setAxisColor('#777E81');
	  	myChart.setAxisValuesColor('#777E81');
	  	
	  	var x=parseInt(windowSizeX()*0.45);
	  	var y=parseInt(windowSizeY()*0.3);
	  	
	  	
	  	myChart.setAxisNameX(' ');
	  	myChart.setAxisNameY(' ');
	  	myChart.setSize(x,y);
	  	myChart.setTitle(' ');

	  	myChart.draw();
    	
    	
      //helloMessage = xmlHttp.responseText;
      //document.getElementById ("widgetDepartments").innerHTML = helloMessage;
    } 
    // a HTTP status different than 200 signals an error
    else 
    {
      alert("There was a problem accessing the server: " + xmlHttp.statusText);
    }
  }
}

// make asynchronous HTTP request using the XMLHttpRequest object 
function showWidgetNewTickets()
{
	  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	  {
	    xmlHttp.open("GET", "dwk_ajax.php?request=showWidgetNewTickets", true);  
	    document.getElementById("widgetNewTickets").style.display = "";
	    xmlHttp.onreadystatechange = handleServerResponse_newTickets;
	    xmlHttp.send(null);
	  }
	  else{
	    setTimeout('showWidgetNewTickets()', 1000);
	  }
}

// executed automatically when a message is received from the server
function handleServerResponse_newTickets() 
{
  // move forward only if the transaction has completed
  if (xmlHttp.readyState == 4) 
  {
    // status of 200 indicates the transaction completed successfully
    if (xmlHttp.status == 200) 
    {
      helloMessage = xmlHttp.responseText;
      document.getElementById ("widgetNewTickets").innerHTML = helloMessage;
    } 
    // a HTTP status different than 200 signals an error
    else 
    {
      alert("There was a problem accessing the server: " + xmlHttp.statusText);
    }
  }
}


// make asynchronous HTTP request using the XMLHttpRequest object 
function showWidgetOverDueTickets()
{
	  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	  {
	    xmlHttp.open("GET", "dwk_ajax.php?request=showWidgetOverDueTickets", true);  
	    document.getElementById("widgetOverDueTickets").style.display = "";
	    xmlHttp.onreadystatechange = handleServerResponse_overDueTickets;
	    xmlHttp.send(null);
	  }
	  else{
	    setTimeout('showWidgetOverDueTickets()', 1000);
	  }
}

// executed automatically when a message is received from the server
function handleServerResponse_overDueTickets() 
{
  // move forward only if the transaction has completed
  if (xmlHttp.readyState == 4) 
  {
    // status of 200 indicates the transaction completed successfully
    if (xmlHttp.status == 200) 
    {
      helloMessage = xmlHttp.responseText;
      document.getElementById ("widgetOverDueTickets").innerHTML = helloMessage;
    } 
    // a HTTP status different than 200 signals an error
    else 
    {
      alert("There was a problem accessing the server: " + xmlHttp.statusText);
    }
  }
}


function windowSizeX() {  
	var dwk_size = 0;  
	if (typeof window.innerWidth != 'undefined'){ 
		dwk_size=innerWidth; 
	}else if (typeof document.documentElement != 'undefined'  
	      && typeof document.documentElement.clientWidth !=  
	      'undefined' && document.documentElement.clientWidth != 0)  
	{ 
		  dwk_size=document.documentElement.clientWidth;
	}  
	else   { 
		dwk_size=document.getElementsByTagName('body')[0].clientWidth; 
	}  
	return dwk_size;  
} 


function windowSizeY() {  
	var dwk_size = 0;  
	if (typeof window.innerWidth != 'undefined'){ 
		dwk_size=innerHeight; 
	}else if (typeof document.documentElement != 'undefined'  
	      && typeof document.documentElement.clientWidth !=  
	      'undefined' && document.documentElement.clientWidth != 0)  
	{ 
		  dwk_size=document.documentElement.clientHeight;
	}  
	else   { 
		dwk_size=document.getElementsByTagName('body')[0].clientHeight; 
	}  
	return dwk_size;  
}