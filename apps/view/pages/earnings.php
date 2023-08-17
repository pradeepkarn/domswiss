<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$earns = [];
if (authenticate() == true) {
    $date = last_active_date($user_id = $_SESSION['user_id']);
    $tree  = my_tree($ref = $_SESSION['user_id'], 1, $data);
    $depth = 1;
    $treeLength = count($tree);
    $sum = calculatePercentageSum($data = $tree, $depth, $treeLength,$_SESSION['user_id']);
    $jsonData = json_encode($tree, JSON_PRETTY_PRINT);
    $file = "jsondata/trees/tree_" . USER['id'] . '.json';
    file_put_contents($file, $jsonData);


    $json_data = file_get_contents(RPATH . "/jsondata/trees/earning_" . USER['id'] . '.json');
    // echo $json_data;
    $earns = json_decode($json_data);
}


?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <!-- <h1 class="mt-4">Dashboard</h1> -->
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">Earnings</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <table id="datatablesSimple" class="table table-bordered">




                                <?php
                                $k = 0;
                                foreach ($earns as $ern) :
                                    if ($ern->commission > 0) :
                                ?>
                                        <thead>
                                            <tr>
                                                <th>Commission</th>
                                                <th colspan="4">Form user</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-success text-white my-2">
                                            <tr>
                                                <td><?php echo $ern->commission; ?></td>
                                                <td colspan="4"><?php echo $ern->user; ?></td>

                                            </tr>
                                            <tr>

                                                <td>Order ID</td>
                                                <td>PV</td>
                                                <td>Percentage</td>
                                                <td>Commission</td>
                                                <td>Order Date</td>

                                            </tr>
                                            <?php
                                            foreach ($ern->purchase_array as $pr) { ?>
                                                <tr>
                                                    <td><?php echo $pr->id; ?></td>

                                                    <td><?php echo $pr->pv; ?></td>
                                                    <td><?php echo ($ern->percentage * 100); ?>%</td>
                                                    <td><?php echo $ern->commission; ?></td>
                                                    <td><?php echo $pr->created_at; ?></td>
                                                </tr>
                                        </tbody>
                                    <?php } ?>

                            <?php
                                        $k++;
                                    endif;

                                endforeach;
                            ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <?php import("apps/view/inc/footer-credit.php"); ?>
    </div>
</div>
<?php
import("apps/view/inc/footer.php");
?>