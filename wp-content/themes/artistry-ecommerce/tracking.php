<script>
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({
  'visitorFirstName' : '<?php global $current_user; get_currentuserinfo(); echo $current_user->user_firstname;?>',
  'visitorLastName' : '<?php global $current_user; get_currentuserinfo(); echo $current_user->user_lastname;?>',
  'visitorEmail' : '<?php global $current_user; get_currentuserinfo(); echo $current_user->user_email;?>',
  'visitorPhone' : '<?php global $current_user; get_currentuserinfo(); echo $current_user->billing_phone;?>'
});
</script>
