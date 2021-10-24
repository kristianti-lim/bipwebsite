<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">

        <div class="col-lg-6">
            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '
          </div>') ?>
            <?= $this->session->flashdata('message') ?>

            <div class="card">
                <div class="card-header">
                    Input kas keluar
                </div>
                <div class="card-body">
                    <form action="<?= base_url('transaksi/kaskeluar') ?>" method="post">
                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="ex: pembayaran .....">
                            <?= form_error('keterangan', '<small class="text-danger pl-3">', ' </small>') ?>

                        </div>
                        <div class="form-group">
                    <input class="form-control" type="date"  id="tanggal" name="tanggal">
                    </div>
                        <div class="form-group">
                            <label for="inputAddress2">Nominal</label>
                            <input type="text" class="form-control" id="nominal" name="nominal" placeholder="ex: 100000">
                            <?= form_error('nominal', '<small class="text-danger pl-3">', ' </small>') ?>

                        </div>

                        <button type="submit" class="btn btn-primary">Tambah kas keluar</button>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!--modal-->
<!-- Button trigger modal -->