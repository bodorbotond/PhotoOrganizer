
function removePhotosFromAlbum(url, albumId)
{
	if (confirm('Are you sure about remove selected photos from this album?'))
	{
		document.getElementById("SelectForm").action = url + 'albums/removePhotos/' + albumId;
		document.getElementById("SelectForm").submit();
	}
}


function setRemoveButtonVisibility()
{

	var selectCheckBoxes = document.getElementsByClassName("imageSelectCheckBox");
	var removeButton = document.getElementById("RemoveButton");
	
	var selected = false;
	
	for (i = 0; i < selectCheckBoxes.length; i++)
	{
		if (selectCheckBoxes[i].checked)
		{
			selected = true;
		}
	}
	
	if (selected)
	{
		if (removeButton.style.display != "inline")
		{
			removeButton.style.display = "inline";
		}
	}
	else
	{
		removeButton.style.display = "none";
	}
}