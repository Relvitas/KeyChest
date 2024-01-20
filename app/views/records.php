<main>
    <div class="container-table">
        <div class="box-1">
            <div class="box-title">
                <span>Registros</span>
            </div>
            <form class="form-search_record" action="?c=record&a=search_record" method="post">
                <button class="search-record-button" type="submit" title="buscar un registro">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <div class="input">
                    <input type="text" name="buscar" class="input-search" placeholder="Buscar">
                </div>
            </form>
            <?= alert($info)?>
        </div>
        <div class="box-2">
            <div class="box-table">
                <table>
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
                        if (!is_string($info)):
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
                                <td><a href="?id=<?= $object->id_registro?>&c=record&a=edit_record" title="editar registro"><i class="fa-solid fa-file-pen"></i></a></td>
                                <td><a href="?c=record&a=confirm_deletion&id_record=<?= base64_encode($object->id_registro)?>" title="eliminar registro"><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                            <!-- 
                                &c=record&a=delete_record
                             -->
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-paginator">
            <?= $pagination ?>
        </div>
    </div>
</main>