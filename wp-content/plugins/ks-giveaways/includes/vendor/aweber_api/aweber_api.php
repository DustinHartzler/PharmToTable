<?php

if (class_exists('KS_Giveaways_Vendor_AWeberAPI')) {
	trigger_error("Duplicate: Another AWeberAPI client library is already in scope.", E_USER_WARNING);
}
else {
	require_once('aweber.php');
}
