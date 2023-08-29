<?php
$context = obj($context);
// myprint($context);
$invoice = $context->payment['invoice'];
$invid = $invoice;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shipping-Label-<?php echo $invid; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


    <style>
        /* table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        } */
    </style>
</head>
<?php

$user_id = $context->payment['user_id'];

$pmtid = $context->payment['id'];
$shadrs = obj(get_shipping_address($user_id));
$user = obj(getData('pk_user', $user_id));

$shpadrs = get_my_primary_address($userid = $user_id);
$invData = get_invoice_address($country_code = $shpadrs->country_code);
$invoice_address = $invData->office;
$bank = $invData->bank;
$delv_info = $invData->delv_info;
// myprint($shadrs);

// myprint($context->payment);
?>

<body>
    <section>
        <div class="container" style="height: 297mm;">
            <div class="row mt-3">
                <div class="col-md-4 mx-auto text-center">
                    <img id="logo" src="/<?php echo home; ?>/media/img/logo-dom-swiss.svg" width="150px" title="Koice" alt="Koice" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-12 text-center">
                    <?php echo $invoice_address; ?>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-3">
                    <p style="font-size: 20px; font-weight:450; width:200px;">
                        Ship to: <br>
                        <?php echo $user->first_name; ?> <br>
                        <?php echo $user->last_name; ?> <br>
                        <?php echo $user->city; ?> <br>
                        <?php echo $user->state; ?> <br>
                        <?php echo $user->country; ?> <br>
                        <?php echo $user->zipcode; ?>
                    </p>

                </div>
                <div class="col-3">

                    <p style="font-size: 20px; font-weight:450; width:200px;">
                        Ship from: <br>
                        <?php echo $invoice_address; ?> <br>
                       
                    </p>
                </div>
                <!-- <div class="col-3">

                    <p style="font-size: 20px; font-weight:470;"> Rechnungsnummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> Rechnungsdatum:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> Bestellnummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;">Ihre UID-Nummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> EORI Nummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> Bezahlart:</p>

                </div> -->
                <div class="col-6 text-end">
                    <p style="font-size: 20px; font-weight:470;">
                        INV-<?php echo $invid; ?> <br>
                        DATE-<?php echo date('Y-m-d H:i:s'); ?> <br>
                        ORD-<?php echo $pmtid; ?>
                    </p>
                    <?php echo $bank; ?> <br>
                    <!-- <p style="font-size: 20px; font-weight:470;">
                        ATEOS1000108119
                        Bank transfer
                        (Prepay)
                    </p> -->
                </div>

            </div>
            <div class="row mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Product ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_amt = 0;
                        $total_pv = 0;
                        $i = 1;


                        foreach ($context->cart as $itm) {
                            $cv = obj($itm);
                            $pkg = obj($cv->package);
                            $j = 1;
                            foreach ($cv->products as $pr) {
                                $itemname = select_col('item', $pr->item, 'name');
                                $pid = select_col('item', $pr->item, 'product_id');
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $pid; ?></td>
                                    <td><?php echo $itemname; ?></td>
                                    <td><?php echo $pr->qty * $pkg->qty; ?> unit</td>
                                </tr>
                            <?php $j++;
                            } ?>

                        <?php $i++;
                        } ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <?php echo $invoice_address; ?>
                </div>
            </div>
        </div>

        <div class="container" style="height: 297mm;">
            <div class="row">
                <div class="col-md-4 mx-auto text-center">
                    <img id="logo" src="/<?php echo home; ?>/media/img/logo-dom-swiss.svg" width="150px" title="Koice" alt="Koice" />
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-12 text-center">
                    <?php echo $invoice_address; ?>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-3">
                    <p style="font-size: 20px; font-weight:450; width:200px;">
                        Ship to: <br>
                        <?php echo $user->first_name; ?> <br>
                        <?php echo $user->last_name; ?> <br>
                        <?php echo $user->city; ?> <br>
                        <?php echo $user->state; ?> <br>
                        <?php echo $user->country; ?> <br>
                        <?php echo $user->zipcode; ?>
                    </p>

                </div>
                <div class="col-3">

                    <p style="font-size: 20px; font-weight:450; width:200px;">
                        Ship from: <br>
                        <?php echo $invoice_address; ?> <br>
                    </p>
                </div>

                <!-- <div class="col-3">

                    <p style="font-size: 20px; font-weight:470;"> Rechnungsnummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> Rechnungsdatum:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> Bestellnummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;">Ihre UID-Nummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> EORI Nummer:</p>
                    <p style="font-size: 20px; font-weight:470; margin-top:-15px;"> Bezahlart:</p>

                </div> -->
                <div class="col-6 text-end">
                    <p style="font-size: 20px; font-weight:470;">
                        INV-<?php echo $invid; ?> <br>
                        DATE-<?php echo date('Y-m-d H:i:s'); ?> <br>
                        ORD-<?php echo $pmtid; ?>
                    </p>
                    <?php echo $bank; ?> <br>
                    <!-- <p style="font-size: 20px; font-weight:470;">
                        ATEOS1000108119
                        Bank transfer
                        (Prepay)
                    </p> -->
                </div>


            </div>
            <div class="row mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Product ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_amt = 0;
                        $total_pv = 0;
                        $i = 1;


                        foreach ($context->cart as $itm) {
                            $cv = obj($itm);
                            $pkg = obj($cv->package);
                            $j = 1;
                            foreach ($cv->products as $pr) {
                                $itemname = select_col('item', $pr->item, 'name');
                                $pid = select_col('item', $pr->item, 'product_id');
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $pid; ?></td>
                                    <td><?php echo $itemname; ?></td>
                                    <td><?php echo $pr->qty * $pkg->qty; ?> unit</td>
                                </tr>
                            <?php $j++;
                            } ?>

                        <?php $i++;
                        } ?>


                    </tbody>
                </table>
            </div>


            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <?php
                    echo $delv_info;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php echo $invoice_address; ?>
                    Email: <a href="mailto:support@domswiss.me">support@domswiss.me</a>
                </div>
            </div>
            <!-- Footer -->
            <footer class="text-center my-5">
                <p class="text-1"><strong>NOTE :</strong> This is computer generated receipt and does not require physical signature.</p>
                <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> Print</a> <a href="javascript:window.print()" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-download"></i> Download</a> </div>
            </footer>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>