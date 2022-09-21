<?php
require_once('header.php');
?>

<div class="py-5 text-center">
    <img class="d-block mx-auto mb-1" src="../assets/brand/mpesa.png" alt="" width="172">
    <h2>STK Complete Checkout</h2>
</div>

<div class="row">
    <div class="card card-body col-sm-5 mx-auto">
        <div id="feedback"></div>
        <form id="form" action="" class="form" autocomplete="FALSE">
            <div class="form-floating mb-3">
                <label for="url">NGROK/Website address</label>
                <input type="text" class="form-control" name="url">
            </div>
            <div class="form-floating">
                <label for="phone">Phone Number</label>
                <input type="number" class="form-control" name="phone">
            </div>
            <div class="form-floating">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" name="amount">
            </div>
            <div class="form-floating">
                <label for="account">Account</label>
                <input type="text" class="form-control" name="account">
            </div>
            <button id="pay" class="btn btn-primary btn-block mt-3">Pay</button>
        </form>
    </div>
</div>

</body>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script>
    $(() => {
        $("#pay").on('click', async (e) => {
            e.preventDefault()

            $("#pay").text('Please wait...').attr('disabled', true)
            const form = $('#form').serializeArray()

            var indexed_array = {};
            $.map(form, function(n, i) {
                indexed_array[n['name']] = n['value'];
            });

            const _response = await fetch('/Mpesa.php', {
                method: 'post',
                body: JSON.stringify(indexed_array),
                mode: 'no-cors',
            })

            const response = await _response.json()
            $("#pay").text('Pay').attr('disabled', false)

            if (response && response.ResponseCode == 0) {
                $('#feedback').html(`<p class='alert alert-success'>${response.CustomerMessage}</p>`)
            } else {
                $('#feedback').html(`<p class='alert alert-danger'>Error! ${response.errorMessage}</p>`)
            }
        })
    })
</script>

</html>