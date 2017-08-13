<?
	require_once("../inc/ajax_init.php");
	if (!isset($_SESSION['farm_id'])):?>
		<ul class="notification-body">
		<li>No Farm selected</li>
		</ul>
	<? exit;
	endif;
	if ($util->GetPost('getstatus') != ''):
		echo $my_db->get_Samxtec_alert($_SESSION['farm_id']);
		exit;
	endif;
	$result = $my_db->zero_Smaxtec_alert($_SESSION['farm_id']);
	if (!$result):?>

		<ul class="notification-body">
			<li>There was an error.</li>
		</ul>
	<?exit;
	endif;
	$events = $my_db->Get_Smaxtec_Events($_SESSION['farm_id']);
	foreach ($events as $event):
	?><ul class="notification-body">
	<li>
		<span class="unread">
			<a href="javascript:void(0);" class="msg">
				<img src="<?= ASSETS_URL; ?>/img/avatars/<?= $event['type'];?>.png" alt="" class="air air-top-left margin-top-5" width="40" height="40" />
				<span class="from"><?= $event['name']?></span>
				<time><?= $event['time'];?></time>
				<span class="subject"><img style="margin-left:20px;" src="<? echo ASSETS_URL; ?>/img/avatars/<?= $event['img'];?>" width="32" height="32"/> <?= $event['description'];?></span>
				<span class="msg-body"></span>
			</a>
		</span>
	</li>
</ul>
<?
endforeach;
?>