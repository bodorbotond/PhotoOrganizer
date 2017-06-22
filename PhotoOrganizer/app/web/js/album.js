
function removePhotosFromAlbum(url, albumId)
{
	if (confirm('Are you sure about remove selected photos from this album?'))
	{
		document.getElementById("SelectForm").action = url + 'albums/removePhotos/' + albumId;
		document.getElementById("SelectForm").submit();
	}
}