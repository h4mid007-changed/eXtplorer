function showLoadingIndicator( el, replaceContent ) {
	if( !el ) return;
	var loadingimg = 'components/com_joomlaxplorer/_img/indicator.gif';
	var imgtag = '<img src="'+ loadingimg + '" alt="Loading..." border="0" name="Loading" align="absmiddle" />';
	
	if( replaceContent ) {
		el.innerHTML = imgtag;
	}
	else {
		el.innerHTML += imgtag;
	}
}
