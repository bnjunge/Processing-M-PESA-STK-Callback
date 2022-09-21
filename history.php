<?php
require_once('header.php');
require_once('dbcon.php');
?>

<div class="py-5 text-center">
    <img class="d-block mx-auto mb-1" src="../assets/brand/mpesa.png" alt="" width="172">
    <h2>STK Payment History</h2>
</div>

<div class="row">
    <div class="table responsive col-sm-8 mx-auto">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Trx Number</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php $data = getData(); ?>

                <?php foreach ($data as $item) : ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= $item->PhoneNumber ?></td>
                        <td><?= $item->Amount ?></td>
                        <td><?= $item->MpesaReceiptNumber ?></td>
                        <td><?= $item->created_at ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>