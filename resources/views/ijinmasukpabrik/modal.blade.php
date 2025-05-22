<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formUpload" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="foto_kendaraan" class="form-label">Foto Kendaraan</label>
                        <input class="form-control" type="file" id="foto_kendaraan" name="foto_kendaraan" accept="image/jpeg, image/png" capture="environment" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_sim" class="form-label">Foto SIM</label>
                        <input class="form-control" type="file" id="foto_sim" name="foto_sim" accept="image/jpeg, image/png" capture="environment" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload & Otorisasi</button>
                </div>
            </div>
        </form>
    </div>
</div>
