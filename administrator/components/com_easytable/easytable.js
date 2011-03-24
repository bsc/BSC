function toggleTick (tFieldName, tRow, tImgSuffix) {
	if(arguments[2] == null) {
		tImgSuffix = '_img';
	}
	
	var tFieldNameRow = tFieldName + tRow;
	var tImageName = tFieldNameRow+tImgSuffix;
	
	var tFieldElementId = eval('document.adminForm.'+tFieldNameRow);
	var tFieldImageElementId = eval('document.'+tImageName);
	
	if(tFieldElementId.value == 1)
	{
		
		tFieldImageElementId.src="images/publish_x.png";
		tFieldElementId.value = 0;
	}
	else
	{
		tFieldImageElementId.src="images/tick.png";
		tFieldElementId.value = 1;
	}
}

function submitbutton(pressbutton)
{
	if(pressbutton =='save' || pressbutton == 'apply')
	{
		if(document.adminForm.easytablename.value == '')
		{
			alert("Please enter the name of the table.");
		}
		else
		{
			submitform(pressbutton);
		}
	}
	else if (pressbutton == 'updateETDTable' || pressbutton == 'createETDTable')
	{
		var tFileName = document.adminForm.tablefile.value;
		var dot = tFileName.lastIndexOf(".");
		if(dot == -1)
		{
			alert ("Only files with a CSV extension are supported. No Extension found.")
		}
		
		var tFileExt = tFileName.substr(dot,tFileName.length);
		tFileExt = tFileExt.toLowerCase();

		if(tFileExt != ".csv")
		{
			alert ("Only files with a CSV extension are supported. Found: "+tFileExt);
		}
		else
		{
			submitform(pressbutton);
		}
	}
	else if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
	}
	else if (pressbutton =='publish' || pressbutton == 'unpublish' || pressbutton =='remove' || pressbutton == 'add'|| pressbutton =='edit' )
	{
		submitform(pressbutton);
	}
	else
	{
		alert("OK - you broke something, not really sure how you got here.  If you want this fixed I'd make some serious notes about how you ended up here. PB->"+pressbutton);
	}
}

function ShowTip(id) {
	document.getElementById(id).style.display = 'block';
}

function HideTip(id) {
	document.getElementById(id).style.display = 'none';
}
