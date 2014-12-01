
function getHTTPObject() 
	{ 
		var xmlhttp; //declare the variable to hold the object.		
		//if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
		//{ 
			try 
			{ 	
				var browser = navigator.appName; //find the browser name
				if(browser == "Microsoft Internet Explorer")
				{
					/* Create the object using MSIE's method */					
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				else
				{
					/* Create the object using other browser's method */
					xmlhttp = new XMLHttpRequest();
				}				
			} 
			catch (e) 
			{ 
				xmlhttp = false;
			} 
		//} 
		
		return xmlhttp; 
	}
 

		
	function getSubInfo(thispage,thisid)
	{  
		 var url = "";
	    thisid++;
	    var url = thispage + "?sub=" + thisid;
	    http = getHTTPObject();
	   	http.open("GET",url,true);
		http.onreadystatechange = showSubInfo;
		http.send(null);
	}
	
		function showSubInfo() {
			
		switch(http.readyState)
		{	
			case 4: 
				//alert(http.responseText);
				document.getElementById("sub_info").innerHTML= http.responseText;
				break;
			default: 
				document.getElementById("sub_info").innerHTML = "Loading...";
				break;
		}
	}
	

	
function getObj(name)
{
  if (document.getElementById) { return document.getElementById(name); }
  else if (document.all) { return document.all[name]; }
  else if (document.layers) { return document.layers[name]; }
}


function getBrowser(name)
{
  if (document.getElementById) { return "IE5"; }
  else if (document.all) { return "IE4"; }
  else if (document.layers) { return "NS"; }
}



function changePage(pageType) {
	
	var browserType = getBrowser("cc_info");
	var visText = "visible";
	var novisText = "hidden";
	if (browserType == "IE5") {
		var visText = "inline-block";
		var novisText = "none";		
	}
	
	if (browserType == "IE5") {
		if ((pageType == "paypalexpress") || (pageType == "paypal")) {
			objName = getObj("cc_info");
			objName.style.display = novisText;
			objName = getObj("billing_address");
			objName.style.display = novisText;
		} else {
			objName = getObj("cc_info");
			objName.style.display = visText;
			objName = getObj("billing_address");
			objName.style.display = visText;
		}
	}
	else if (browserType == "IE4") {
		if ((pageType == "paypalexpress") || (pageType == "paypal")) {
			objName = getObj("cc_info");
			objName.style.visibility = novisText;
			objName = getObj("billing_address");
			objName.style.visibility = novisText;
		} else {
			objName = getObj("cc_info");
			objName.style.visibility = visText;
			objName = getObj("billing_address");
			objName.style.visibility = visText;
		}
	}
	else {
		if ((pageType == "paypalexpress") || (pageType == "paypal")) {
			objName = getObj("cc_info");
			objName.style.visibility = novisText;
			objName = getObj("billing_address");
			objName.style.visibility = novisText;
			
		} else {
			objName = getObj("cc_info");
			objName.style.visibility = visText;
			objName = getObj("billing_address");
			objName.style.visibility = visText;
		}
	}
	
}
