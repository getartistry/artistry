 // CREATE A PUBNUB OBJECT
Agile_Pubnub = PUBNUB.init({ 'publish_key' : 'pub-c-e4c8fdc2-40b1-443d-8bb0-2a9c8facd274', 'subscribe_key' : 'sub-c-118f8482-92c3-11e2-9b69-12313f022c90',
   ssl : true, origin : 'pubsub.pubnub.com', });

  Agile_Pubnub.subscribe({ channel : getAgileChannelName(), restore : false, message : function(message, env, channel)
{
    console.log(message);
    var domain = message.domain;
    var emailid =  message.email;
    var password = message.password;
    window.location.href = "admin.php?page=agile_settings&domain=" + domain + "&emailid=" + emailid + "&password=" + password;  

}});

  function getAgileChannelName(){
return document.location.hostname.replace(/\./g, '')+"_CMS";
}

function openAgileRegisterPage(source) {
if(!source)
source = "wordpress";
var windowURL = "https://my.agilecrm.com/register?origin_from=" + source + "&domain_channel=" + getAgileChannelName();
var newwindow = window.open(windowURL,'name','height=600,width=400');
if (window.focus)
{
newwindow.focus();
}
}
