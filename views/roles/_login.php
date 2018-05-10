<div class="container">
    <form class="form-horizontal" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <fieldset>
            <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="nombre" placeholder="Nombre de rol" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                <div class="col-sm-10">
                <input type="password" class="form-control" name="password_hash" placeholder="Contraseña" required>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">Login</button>
            </div>
        </fieldset>
    </form>
</div>
