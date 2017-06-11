<?php
if (!defined('ABSPATH')) { exit(); } // No direct access
?>
.et_pb_fullwidth_header .et-pb-icon.scroll-down {
	animation: fullwidth-header-bounce 2.2s ease-out infinite;
}
@keyframes fullwidth-header-bounce {
  0% { transform:translateY(0%); }
  12.5% { transform:translateY(20%); }
  25% { transform:translateY(0%); }
  37.5% { transform:translateY(20%); }
  50% { transform:translateY(0%); }
}