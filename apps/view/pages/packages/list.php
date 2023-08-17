<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$plobj = new Model('item');
$pl = $plobj->filter_index(['item_group' => 'package'],'ASC');
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">



        <main>
            <div class="container-fluid px-4">
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">All Packages</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                    <div class="col-md-4 my-3">
                            <div class="d-grid">
                            <a class="btn btn-dark" href="/<?php echo home; ?>/create-package">Add Package</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table id="datatablesSimple1" class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">PV</th>
                                        <th scope="col">RV</th>
                                        <th scope="col">Direct Bonus</th>
                             
                                        <th scope="col">Status</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">Edit</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pl as $key => $pv) :
                                        $pv = obj($pv);
                                       
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $pv->product_id; ?></th>
                                            <th>
                                                <img style="width:100%; max-height:30px; object-fit:cover;" id="banner" src="/<?php echo MEDIA_URL; ?>/upload/items/<?php echo $pv->image; ?>" alt="">
                                            </th>
                                            <td><?php echo $pv->name; ?></td>
                                            <td><?php echo $pv->pv; ?></td>
                                            <td><?php echo $pv->rv; ?></td>
                                            <td><?php echo $pv->direct_bonus; ?></td>
                                    
                                            <td><?php echo $pv->is_active?'Active':'Inactive'; ?></td>
                                            <td><?php echo $pv->created_at; ?></td>
                                            <td>
                                                <a class="btn-primary btn btn-sm" href="/<?php echo home . "/edit-package/?pid=" . $pv->id; ?>">Edit</a>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
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