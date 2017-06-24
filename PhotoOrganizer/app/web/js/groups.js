function removeFromGroup(url, groupId)
{
	if (confirm('Are you sure about remove selected items from this group?'))
	{
		document.getElementById("SelectForm").action = url + 'groups/remove/' + groupId;
		document.getElementById("SelectForm").submit();
	}
}