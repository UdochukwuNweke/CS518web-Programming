/*https://stackoverflow.com/a/979996*/
function processURL()
{
	var params = {};

	var aTag = document.createElement('a');
	aTag.href = window.location.href;

    if ( aTag.search.length != 0 ) 
    {
	    var parts = aTag.search.substring(1).split('&');

	    for (var i = 0; i < parts.length; i++) 
	    {
	        var nv = parts[i].split('=');
	        if (!nv[0]) continue;
	        params[nv[0]] = nv[1] || true;
	    }
	}

	return params;
}