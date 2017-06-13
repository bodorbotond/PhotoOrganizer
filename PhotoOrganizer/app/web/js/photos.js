
function changeSelectVisibility()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");	// get all checkbox
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		selectCheckBoxes[i].style.display = "block";								// set visible		
	}	

}

function changeAllSelectVisibility()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	var selectAllButton = document.getElementById("SelectAllButton");
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		selectCheckBoxes[i].style.display = "block";								// set all visible
		selectCheckBoxes[i].checked = true;											// set all selected
		document.getElementById("SetVisibilityButton").style.display = "inline";
		document.getElementById("DeleteButton").style.display = "inline";
	}	

}

function clearSelection()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		selectCheckBoxes[i].style.display = "none";								// set all visible
		selectCheckBoxes[i].checked = false;									// set all selected
		document.getElementById("SetVisibilityButton").style.display = "none";
		document.getElementById("DeleteButton").style.display = "none";
	}
}

function checkSelection()								// check there is selected photo
{
	var userPhotosContainer = document.getElementById("UserPhotos");
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{
		if (selectCheckBoxes[i].checked)
		{
			document.getElementById("SetVisibilityButton").style.display = "inline";
			document.getElementById("DeleteButton").style.display = "block";
		}
	}
}


function deletePhotos(url)
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");	// get all checkbox
	var selected = false;
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		if (selectCheckBoxes[i].checked == true)		// if at least one checkbox is selected
		{
			selected = true;
		}
	}
	
	if (selected)			// if at least one checkbox is selected
	{
		if (confirm('You are sure about delete selected photos?'))
		{
			document.getElementById("SelectForm").action= url + 'photos/select/d';
			document.getElementById("SelectForm").submit();
		}
	}
	else
	{
		document.getElementById("SelectErrorMessage").style.display = "block";
	}
}


function changePhotosVisibilityToPrivate(url)
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");	// get all checkbox
	var selected = false;
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		if (selectCheckBoxes[i].checked == true)		// if at least one checkbox is selected
		{
			selected = true;
		}
	}
	
	if (selected)			// if at least one checkbox is selected
	{
		document.getElementById("SelectForm").action= url + 'photos/select/pr';
		document.getElementById("SelectForm").submit();
	}
	else
	{
		document.getElementById("SelectErrorMessage").style.display = "block";
	}
}


function changePhotosVisibilityToPublic(url)
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");	// get all checkbox
	var selected = false;
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		if (selectCheckBoxes[i].checked == true)		// if at least one checkbox is selected
		{
			selected = true;
		}
	}
	
	if (selected)			// if at least one checkbox is selected
	{
		document.getElementById("SelectForm").action= url + 'photos/select/pb';
		document.getElementById("SelectForm").submit();
	}
	else
	{
		document.getElementById("SelectErrorMessage").style.display = "block";
	}
}