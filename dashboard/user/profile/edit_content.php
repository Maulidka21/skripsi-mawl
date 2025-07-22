<form method="POST">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name'] ?? '') ?>"
                    required>
            </div>
            <div class="form-group">
                <label>Kode</label>
                <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($data['code'] ?? '') ?>"
                    required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control"
                    value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Umur</label>
                <input type="number" name="umur" class="form-control"
                    value="<?= htmlspecialchars($data['umur'] ?? '') ?>">
            </div>
            <hr>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control"
                    value="<?= htmlspecialchars($data['username']) ?>" required>
            </div>
            <div class="form-group">
                <label>Password Baru (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
        </div>
        <div class="card-footer">
            <a href="index.php" class="btn btn-secondary btn-sm">Batal</a>
            <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
        </div>
    </div>
</form>