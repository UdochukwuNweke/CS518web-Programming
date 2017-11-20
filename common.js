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

//credit: https://gist.github.com/kottenator/9d936eb3e4e3c3e02598
function pagination(c, m) 
{
    var current = c,
        last = m,
        delta = 2,
        left = current - delta,
        right = current + delta + 1,
        range = [],
        rangeWithDots = [],
        l;

    for (let i = 1; i <= last; i++) {
        if (i == 1 || i == last || i >= left && i < right) {
            range.push(i);
        }
    }

    for (let i of range) {
        if (l) {
            if (i - l === 2) {
                rangeWithDots.push(l + 1);
            } else if (i - l !== 1) {
                rangeWithDots.push('...');
            }
        }
        rangeWithDots.push(i);
        l = i;
    }

    return rangeWithDots;
}

function httpPost(obj, postURI, callback)
{
    var xhr = new XMLHttpRequest();
    xhr.open('POST', postURI);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () 
    {
        if (xhr.readyState == 4 && xhr.status == 200) 
        {
            callback(xhr.responseText);
        }
    }

    xhr.onerror = function()
    {
        console.log('\thttpPost(): Network error.');
        callback({});
    };

    xhr.send( JSON.stringify(obj) );
}