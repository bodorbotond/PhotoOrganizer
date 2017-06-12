
function changeSelectVisibility()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");	// get all checkbox
	var selectButton = document.getElementById("SelectButton");		// get selectButton's text
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		if (selectCheckBoxes[i].style.display == "block")							// if checkboxes are visible
		{
			selectCheckBoxes[i].style.display = "none";									// set hidden
			selectCheckBoxes[i].checked = false;										// set deselect
		}
		else																		// else
		{
			selectCheckBoxes[i].style.display = "block";								// set visible
		}		
	}
	
	//change select button's text
	
	if(selectButton.innerHTML == "Select")
	{
		selectButton.innerHTML = "Deselect";
	}
	else
	{
		selectButton.innerHTML = "Select";
	}	

}

function changeAllSelectVisibility()
{
	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	var selectAllButton = document.getElementById("SelectAllButton");
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{ 
		if (selectCheckBoxes[i].style.display == "block")							// if checkboxes are visible
		{
			selectCheckBoxes[i].style.display = "none";									// set all hidden
			selectCheckBoxes[i].checked = false;										// set all deselect
		}
		else
		{
			selectCheckBoxes[i].style.display = "block";								// set all visible
			selectCheckBoxes[i].checked = true;											// set all selected
		}
	}
	
	//change select button's text
	
	if(selectAllButton.innerHTML == "Select All")
	{
		selectAllButton.innerHTML = "Deselect All";
	}
	else
	{
		selectAllButton.innerHTML = "Select All";
	}	

}

function submitSelectButton()
{
	if (confirm('You are sure about delete selected photos?'))
	{
		document.getElementById("SelectForm").submit();
	}
}