
function setHiddenPhotosMenuItems()
{
	document.getElementById("AddToButton").style.display = "none";
	document.getElementById("SetVisibilityButton").style.display = "none";
	document.getElementById("EditButton").style.display = "none";
	document.getElementById("EditMoreButton").style.display = "none";
	document.getElementById("DeleteButton").style.display = "none";
}



function setVisiblePhotosMenuItems()
{
	document.getElementById("AddToButton").style.display = "inline";
	document.getElementById("SetVisibilityButton").style.display = "inline";
	document.getElementById("DeleteButton").style.display = "inline";
}



function setVisibleEditMenuItem(numberOfSelected)
{
	if (numberOfSelected > 1)
	{
		document.getElementById("EditButton").style.display = "none";
		document.getElementById("EditMoreButton").style.display = "inline";
	}
	else
	{
		document.getElementById("EditMoreButton").style.display = "none";
		document.getElementById("EditButton").style.display = "inline";
	}
	
}



window.onload = setHiddenPhotosMenuItems;



function setCheckBoxesVisible()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");	// get all checkbox
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		selectCheckBoxes[i].style.display = "block";								// set visible		
	}	

}



function setAllCheckBoxesVisibleAndChecked()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	var selectAllButton = document.getElementById("SelectAllButton");
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		selectCheckBoxes[i].style.display = "block";								// set all visible
		selectCheckBoxes[i].checked = true;											// set all selected
	}
	
	setVisiblePhotosMenuItems();
	setVisibleEditMenuItem(2);

}



function clearSelection()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		selectCheckBoxes[i].style.display = "none";								// set all hidden
		selectCheckBoxes[i].checked = false;									// set all deselected
	}
	
	setHiddenPhotosMenuItems();
}



function submitForm(url, action)												// submit select form and redirect by passed action parameter
{
	document.getElementById("SelectForm").action= url + 'photos/select/' + action;
	document.getElementById("SelectForm").submit();
}



function deletePhotos(url)
{
	if (confirm('You are sure about delete selected photos?'))
	{
		document.getElementById("SelectForm").action= url + 'photos/select/d';
		document.getElementById("SelectForm").submit();
	}
}

function checkSelection()								// check there is selected photo
{
	var userPhotosContainer = document.getElementById("UserPhotos");
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	
	var selected = false;
	var numberOfSelected = 0;
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{
		if (selectCheckBoxes[i].checked)
		{
			selected = true;
			numberOfSelected++;
		}
	}
	
	if (selected)
	{
		setVisiblePhotosMenuItems();
		setVisibleEditMenuItem(numberOfSelected);
	}
	else
	{
		setHiddenPhotosMenuItems();
	}
}