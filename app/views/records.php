<main>
    <div>
        <table>
            <caption>Registros</caption>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sitio Web</th>
                    <th>Nombre Usuario</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Contraseña</th>
                    <th>Clave Recuperación</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 0;
                foreach($info as $object):
                    $counter++;
                ?>
                    <tr>
                        <td><?= $counter ?></td>
                        <td><?= $object->sitio_web ?></td>
                        <td><?= $object->nombre_usuario == null ? 'N/A' : $object->nombre_usuario ?></td>
                        <td><?= $object->telefono == null ? 'N/A' : $object->telefono ?></td>
                        <td><?= $object->correo == null ? 'N/A' : $object->correo ?></td>
                        <td><?= $object->contrasenia?></td>
                        <td><?= $object->clave_recuperacion == null ? 'N/A' : $object->clave_recuperacion ?></td>
                    </tr>
                <?php 
                endforeach;
                ?>
            </tbody>
        </table>
        <!-- Paginación -->
        <div>
            <?= $pagination ?>
        </div>
    </div>
</main>