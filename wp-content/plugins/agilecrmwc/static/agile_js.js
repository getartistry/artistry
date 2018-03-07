!function(d, s, id)
{
	var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
	if (!d.getElementById(id))
	{
		js = d.createElement(s);
		js.id = id;
		js.src = p + '://platform.twitter.com/widgets.js';
		fjs.parentNode.insertBefore(js, fjs);
	}
}(document, 'script', 'twitter-wjs');

(function(d, s, id)
{
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id))
		return;
	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

document.getElementById('submit_button').style.marginBottom = "20px";
document.getElementById('settings_form').style.marginRight = "20px";
document.getElementById('create_account').style.marginTop = "20px";
document.getElementById('create_account').style.marginLeft = "50px";
document.getElementById('create_account_text').style.marginBottom = "20px";
document.getElementById('create_account_text').style.marginRight = "20px";

function getInputElement(name)
{
	return document.getElementsByName(name)[0];
}

function getErrorElement()
{
	return document.getElementById('error_text');
}

function showError(name, msg, title)
{
	getErrorElement().style.color = "#dd0000";
	getInputElement(name).style.borderColor = "#dd0000";
	if (title)
		getInputElement(title).style.borderColor = "#dd0000";

	if (getErrorElement().innerHTML)
		getErrorElement().innerHTML = msg + ", " + getErrorElement().innerHTML;
	else
		getErrorElement().innerHTML = msg;
}

function successMessage(msg)
{
	getErrorElement().style.color = "#005500";
	getErrorElement().innerHTML = msg;
	setTimeout(function()
	{
		getErrorElement().innerHTML = '';
	}, 5000);
}

function hideError(name)
{
	getInputElement(name).style.borderColor = "#dfdfdf";
	getErrorElement().innerHTML = '';
}
function isFilled(name)
{
	var value = getInputElement(name).value;
	return !(!value || value.length == 0 || value.indexOf(' ') != -1);
}

document.getElementById('submit_button').onclick = function(e)
{
	e.preventDefault();
	document.getElementById('submit_button').value = 'Saving Changes...';
	hideError('agile-domain-setting');
	hideError('agile-key-setting');
	var domain = getInputElement('agile-domain-setting').value;
	var key = getInputElement('agile-key-setting').value;
	if (isFilled('agile-domain-setting') && isFilled('agile-key-setting'))
	{
		if (getInputElement('agile-domain-setting').value.length < 2)
		{
			showError('agile-domain-setting', 'Enter a valid domain');
			document.getElementById('submit_button').value = 'Save Changes';
			return false;
		}
		jQuery.ajax({ url : 'https://' + domain + '.agilecrm.com/core/js/api/email?id=' + key + '&email=as', type : 'GET', dataType : 'jsonp',
			success : function(json)
			{
				if (json.hasOwnProperty('error'))
				{
					showError('agile-key-setting', 'Invalid api key or domain name', 'agile-domain-setting');
					document.getElementById('submit_button').value = 'Save Changes';
					return false;
				}
				document.getElementById('settings_form').submit();
				document.getElementById('submit_button').value = 'Save Changes';
				successMessage('Settings saved successfully');
				return;
			} });
	}
	else
	{
		if (!isFilled('agile-domain-setting'))
			showError('agile-domain-setting', 'Enter a valid domain');
		if (!isFilled('agile-key-setting'))
			showError('agile-key-setting', 'Enter a valid api key');
		document.getElementById('submit_button').value = 'Save Changes';
		return false;
	}
};

jQuery(document).ready(function(){ 

jQuery('#sync_customers').change(function() {	
	var isChecked = jQuery("#sync_customers").is(':checked');
    if(!isChecked) {
		jQuery("#sync_orders").prop( "checked", false );
		jQuery("#sync_orders").prop( "disabled", true );
    } else {
		jQuery("#sync_orders").prop( "disabled", false );
    }
    jQuery("#sync_orders").trigger( "change" );
});

jQuery('#sync_orders').change(function() {	
	var isChecked = jQuery("#sync_orders").is(':checked');
    if(!isChecked) {
		jQuery("#sync_product_tags").prop( "checked", false );
		jQuery("#sync_product_tags").prop( "disabled", true );
                jQuery("#sync_category_tags").prop( "checked", false );
		jQuery("#sync_category_tags").prop( "disabled", true );
    } else {
		jQuery("#sync_product_tags").prop( "disabled", false );
                jQuery("#sync_category_tags").prop( "disabled", false );
    }
});

jQuery("#sync_customers").trigger( "change" );
});

