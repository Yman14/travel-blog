<?php
$page = $_GET['page'];

if($page == 'here'){
    echo "Its here.";
}
elseif($page == 'there'){
    echo "No. Its there!";
}
?>
<div class="greeting">Hi <?=$content?></div>
<button>Helol</button>