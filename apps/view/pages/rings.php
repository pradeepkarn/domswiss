<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<style>
	.ring-section {
		margin-top: 120px;
		max-width: 100vw;
		overflow-x: scroll;
		/* max-height: 100vh; */
	}

	.outer {
		width: 200px;
		height: 200px;
		border-radius: 50%;
		position: relative;
		background-color: #9273B0;
		margin: 10px;
		cursor: pointer;
	}

	.inner {
		position: absolute;
		width: 90%;
		height: 90%;
		border-radius: 50%;
		background-color: #ffffff;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		transition: all 0.5s ease-out;
	}

	.outer:hover .inner {
		width: 95%;
		height: 95%;
	}

	.middle {
		position: absolute;
		width: 70%;
		height: 70%;
		border-radius: 50%;
		background-color: #ffffff;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	.outer:hover .middle {
		width: 75%;
		height: 75%;
	}
</style>
<section class="ring-section">
	<?php
	$file = RPATH . "/jsondata/trees/tree_" . USER['id'] . ".json";
	$tree = json_decode(file_get_contents($file), true);
	?>
	<div class="outer">
		<div class="inner"></div>
		<div class="middle"></div>
	</div>
</section>
<?php
import("apps/view/inc/footer.php");
?>