 
  // CREATE A PUBNUB OBJECT
  Agile_Pubnub = PUBNUB.init({ 'publish_key' : 'pub-c-e4c8fdc2-40b1-443d-8bb0-2a9c8facd274', 'subscribe_key' : 'sub-c-118f8482-92c3-11e2-9b69-12313f022c90',
      ssl : true, origin : 'pubsub.pubnub.com', });
  Agile_Pubnub.subscribe({ channel : getAgileChannelName(), restore : false, message : function(message, env, channel)
{
    console.log(message);
    var action = message.action;
    var name = message.type;
    if(name== 'WebRule'){
      window.location.href = "admin.php?page=agile_webrules";
    }else if(name== 'LandingPages'){
      window.location.href = "admin.php?page=agile_landing";
    }else if(name=='Forms'){
      window.location.href = "admin.php?page=agile_formbuilder";
    }else if(name=='Campaigns'){
      window.location.href = "admin.php?page=agile_email";
    }
}});

 