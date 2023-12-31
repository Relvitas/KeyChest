<main>
    <div>
        <fieldset>
            <legend>Editar Registro</legend>
            <form action="?c=record&a=update_record" method="post">
                <div>
                    <label for="website">Sitio Web</label>
                    <input type="text" name="website" id="website" value="<?= $info->sitio_web?>" required>
                    <input type="hidden" name="id_website" value="<?= $info->id_portal_registro?>">
                </div>
                <div>
                    <label for="username">Nombre Usuario</label>
                    <input type="text" name="username" id="username" value="<?= $info->nombre_usuario?>">
                    <input type="hidden" name="id_username" value="<?= $info->id_nombre_registro?>">
                </div>
                <div>
                    <label for="email">Correo</label>
                    <input type="email" name="email" id="email" value="<?= $info->correo ?>">
                    <input type="hidden" name="id_email" value="<?= $info->id_correo_registro?>">
                </div>
                <div>
                    <label for="tel">Teléfono</label>
                    <input type="tel" name="tel" id="tel" value="<?= $info->telefono ?>">
                    <input type="hidden" name="id_tel" value="<?= $info->id_telefono_registro?>">
                </div>
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" value="<?= $info->contrasenia?>" required>
                </div>
                <div>
                    <label for="recoveryKey">Clave de Recuperación</label>
                    <textarea name="recoveryKey" id="recoveryKey" cols="20" rows="5"><?= $info->clave_recuperacion?></textarea>
                </div>
                <div>
                    <input type="hidden" name="id_record" value="<?= $info->id_registro?>">
                </div>
                <div>
                    <button type="submit" name="save_record">Guardar</button>
                </div>
            </form>
            <div>
                <a href="?c=record&a=list_record"><button type="button">Cancelar</button></a>
            </div>
        </fieldset>
    </div>
</main>