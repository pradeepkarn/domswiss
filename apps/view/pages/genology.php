<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
?>
<div id="layoutSidenav">
	<?php import("apps/view/inc/sidebar.php"); ?>
	<div id="layoutSidenav_content">
		<main>
			<?php
			if (authenticate() == true) {
				// $last_date = last_active_date($user_id = $_SESSION['user_id']);
				// $tree  = my_tree($ref = $_SESSION['user_id'], 1, $last_date);
				// $depth = 1;
				// $treeLength = count($tree);
				// $sum = calculatePercentageSum($data = $tree, $depth, $treeLength, $_SESSION['user_id']);
				// $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
				// $file = "jsondata/trees/tree_" . USER['id'] . '.json';
				// file_put_contents($file, $jsonData);
			}
			?>
			<style>
				/*Now the CSS*/
				/* * {
					margin: 0;
					padding: 0;
				} */

				.tree-section {
					margin-top: 100px;
					max-width: 100vw;
					overflow-x: scroll;
					/* max-height: 100vh; */
				}
			</style>
			<section class="tree-section">
				<?php
				// $file = RPATH . "/jsondata/trees/tree_" . USER['id'] . ".json";
				// $tree = json_decode(file_get_contents($file), true);
				$pvctrl = new Pv_ctrl;
				$pvctrl->db = new Dbobjects;
				$act_data = $pvctrl->am_i_active($_SESSION['user_id']);
				$tree = $pvctrl->my_tree($_SESSION['user_id'], 1);
				?>
				<div class="tree">
					<ul>
						<li data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo USER['id']; ?>">
							<a href="javascript:void(0)">
								<?php echo USER['username']; ?>
								<br>
								<img class="tree-user" src="/<?php echo home; ?>/media/img/user-blank.png" alt="" width="80px" height="80px">
							</a>
							<?php
							echo family_tree($tree);
							?>
						</li>
					</ul>
				</div>
			</section>
			<!-- Modal -->
			<?php
			if (is_superuser()) {
			?>
				<div class="modal fade" id="user-detail-model" tabindex="-1" aria-labelledby="user-detail-modelLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h1 class="modal-title fs-5" id="user-detail-modelLabel">Modal title</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">

								<form class="material-form" action="">
									<div class="form-group row mb-4">
										<div class="col-12">
											<div id='set-tree-user'></div>
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>

			<script>
				const userDetails = document.querySelectorAll('.user-img-icon-tree');
				userDetails.forEach((e) => {
					e.addEventListener('click', () => {
						// document.getElementById('set-tree-user').value = e.getAttribute('data-tree-username');
						$.ajax({
							url: 'user-detail-ajax',
							type: 'POST',
							data: {
								username: e.getAttribute('data-tree-username') // Replace 'your_username' with the actual username
							},
							success: function(response) {
								// Handle the server response
								$('#set-tree-user').html(response);
							},
							error: function(xhr) {
								// Handle any error that occurred during the Ajax request
								$('#set-tree-user').html('Error: ' + xhr.status);
							}
						});
					})
				})
			</script>
		</main>
		<?php import("apps/view/inc/footer-credit.php"); ?>
	</div>
</div>
<?php
import("apps/view/inc/footer.php");
?>