<?php
import("apps/view/inc/header.php");
import("apps/view/inc/navbar.php");
$plobj = new Model('item');
$pl = $plobj->filter_index(['item_group' => 'product'],"ASC");
?>
<div id="layoutSidenav">
    <?php import("apps/view/inc/sidebar.php"); ?>
    <div id="layoutSidenav_content">



        <main>
            <div class="container-fluid px-4">
                <ol class="breadcrumb mt-3 mb-4">
                    <li class="breadcrumb-item active">All Products</li>
                </ol>

                <div class="container">
                    <div class="row mb-4">
                    <div class="col-md-4 my-3">
                            <div class="d-grid">
                            <a class="btn btn-dark" href="/<?php echo home; ?>/create-product">Add Product</a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table id="datatablesSimple1" class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>

                                        <th scope="col">Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Publish Date</th>
                                        <th scope="col">Edit</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pl as $key => $pv) :
                                        $pv = obj($pv);
                                        $cat = getData('item',$pv->parent_id);
                                        $cat_name = $cat?$cat['name']:'No category';
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $pv->product_id; ?></th>
                                           
                                            <td><?php echo $pv->name; ?></td>
                                            <td><?php echo $cat_name; ?></td>
                                            <td><?php echo $pv->is_active?'Active':'Inactive'; ?></td>
                                            <td><?php echo $pv->created_at; ?></td>
                                            <td>
                                                <a class="btn-primary btn btn-sm" href="/<?php echo home . "/edit-product/?pid=" . $pv->id; ?>">Edit</a>
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