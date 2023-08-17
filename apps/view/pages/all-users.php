<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
// myprint($context);
// $db = new Model('pk_user');
// $total_user = $db->index(ord: "DESC", limit: 10000);
// $tp = count($total_user);
$cp = isset($context['data']->current_page) ? $context['data']->current_page : 0;
$tp = isset($context['data']->total_users) ? $context['data']->total_users : 5;
// myprint($context);
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">All Users</li>
                </ol>
                <div class="row">
                    <div class="col-md-4">
                        <form method="get" action="/<?php echo home; ?>/all-users/">
                            <div class="d-flex">
                                <input value="<?php echo isset($_GET['q']) ? $_GET['q'] : null; ?>" type="search" name="q" placeholder="Search from server" class="form-control">
                                <button type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="custom-pagination my-3">
                    <?php
                    $pg = isset($_GET['page']) ? $_GET['page'] : 1;
                    $tp = $tp; // Total pages
                    $current_page = $cp; // Assuming first page is the current page
                    $link = "/all-users/"; // Set your link here

                    // Calculate start and end page numbers to display
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($start_page + 4, $tp);

                    // Show first page button if not on the first page
                    if ($current_page > 1) {
                        echo '<a class="first-button" href="/' . home . $link . '?page=1">&laquo;</a>';
                    }

                    // Show ellipsis if there are more pages before the start page
                    if ($start_page > 1) {
                        echo '<span>...</span>';
                    }

                    // Display page links within the range
                    for ($i = $start_page; $i <= $end_page; $i++) {
                        $active_class = ($pg == $i) ? "btn btn-primary" : null;
                        echo '<a class="' . $active_class . '" href="/' . home . $link . '?page=' . $i . '"><span style="position:relative; top:-5px;">' . $i . '</span></a>';
                    }

                    // Show ellipsis if there are more pages after the end page
                    if ($end_page < $tp) {
                        echo '<span>...</span>';
                    }

                    // Show last page button if not on the last page
                    if ($current_page < $tp) {
                        echo '<a class="last-button" href="/' . home . $link . '?page=' . $tp . '">&raquo;</a>';
                    }
                    ?>
                </div>
                <div class="container">


                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Edit user</th>
                                        <th>username</th>
                                        <th>Email</th>
                                        <th>sponser username</th>
                                        <th>Reg. date</th>
                                        <!-- <th>Wallet</th> -->
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Edit user</th>
                                        <th>username</th>
                                        <th>Email</th>
                                        <th>sponser username</th>
                                        <th>Reg. date</th>
                                        <!-- <th>Wallet</th> -->
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (authenticate() == true) {
                                        $userObj = new Model('pk_user');
                                        $arr = null;
                                        $arr['ref'] = $_SESSION['user_id'];
                                        $partner = $userObj->filter_index($assoc_arr = $arr, $ord = 'DESC', $limit = 999,                                         $change_order_by_col = "");
                                    }
                                    $partner = isset($context['data']->users) ? $context['data']->users : $partner;
                                    foreach ($partner as $value) {
                                        $sponser = sponser_username($value['ref']);
                                    ?>
                                        <tr>
                                            <th><?php echo $value['id']; ?></th>
                                            <th><a target="_blank" href="/<?php echo home; ?>/edit-user/?userid=<?php echo $value['id']; ?>">Edit</a></th>
                                            <th><?php echo $value['username']; ?></th>
                                            <th><?php echo $value['email']; ?></th>
                                            <th><?php echo $sponser; ?></th>
                                            <th><?php echo $value['created_at']; ?></th>
                                            <!-- <th>
                                                <a target="_blank" href="/<?php // echo home; ?>/user-credits/?userid=<?php // echo $value['id']; ?>">Wallet</a>
                                            </th> -->
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                window.addEventListener('load', function() {
                    var inputElement = document.getElementsByClassName("datatable-input")[0];
                    if (inputElement) {
                        inputElement.placeholder = "Search on below table";
                    }
                });
            </script>
        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>