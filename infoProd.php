<?php
include './library/configServer.php';
include './library/consulSQL.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Mesa Redonda | Detalles</title>
    <?php include './inc/link.php'; ?>
</head>

<body id="container-page-product">
    <?php include './inc/navbar.php'; ?>
    <section id="infoproduct">
        <div class="container">
            <div class="row">
                <div class="page-header">
                    <h1>DETALLES DEL PRODUCTO <img src="assets/img/logo2.png" alt="logo" class="img-responsive" style="width: 10%;"></h1>
                </div>
                <?php 
                    $CodigoProducto=consultasSQL::clean_string($_GET['CodigoProd']);
                    $productoinfo=  ejecutarSQL::consultar("SELECT producto.CodigoProd,producto.NombreProd,producto.CodigoCat,categoria.Nombre,producto.Precio,producto.Descuento,producto.Stock,producto.Imagen,producto.Piezas,producto.Edad FROM categoria INNER JOIN producto ON producto.CodigoCat=categoria.CodigoCat  WHERE CodigoProd='".$CodigoProducto."'");
                    while($fila=mysqli_fetch_array($productoinfo, MYSQLI_ASSOC)){
                        echo '
                            <div class="col-xs-12 col-sm-6">
                                <h3 class="text-center">Información del producto</h3>
                                <br><br>
                                <h4><strong>Nombre: </strong>'.$fila['NombreProd'].'</h4><br>
                                <h4><strong>Categoría: </strong>'.$fila['Nombre'].'</h4><br>
                                <h4><strong>Numero de Jugadores: </strong>'.$fila['Piezas'].'</h4><br>
                                <h4><strong>Edades: </strong>'.$fila['Edad'].'</h4><br>
                                <h4><strong>Precio: </strong>$'.number_format(($fila['Precio']-($fila['Precio']*($fila['Descuento']/100))), 2, '.', '').'</h4><br>
                                <h4><strong>Cantidad: </strong>'.$fila['Stock'].'</h4>';
                                if($fila['Stock']>=1){
                                    if($_SESSION['nombreAdmin']!="" || $_SESSION['nombreUser']!=""){
                                        echo '<form action="process/carrito.php" method="POST" class="FormCatElec" data-form="">
                                            <input type="hidden" value="'.$fila['CodigoProd'].'" name="codigo">
                                            <label class="text-center"><small>Agrega la cantidad de productos que añadiras al carrito de compras (Maximo '.$fila['Stock'].' productos)</small></label>
                                            <div class="form-group">
                                                <input type="number" class="form-control" value="1" min="1" max="'.$fila['Stock'].'" name="cantidad">
                                            </div>
                                            <button class="btn btn-lg btn-raised btn-success btn-block"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp; Añadir al carrito</button>
                                        </form>
                                        <div class="ResForm"></div>';
                                    }else{
                                        echo '<p class="text-center"><small>Para agregar productos al carrito de compras debes iniciar sesion</small></p><br>';
                                        echo '<button class="btn btn-lg btn-raised btn-primary btn-block" data-toggle="modal" data-target=".modal-login"><i class="fa fa-user"></i>&nbsp;&nbsp; Iniciar sesion</button>';
                                    }
                                }else{
                                    echo '<p class="text-center text-danger lead">No hay existencias de este producto</p><br>';
                                }
                                if($fila['Imagen']!="" && is_file("./assets/img-products/".$fila['Imagen'])){ 
                                    $imagenFile="./assets/img-products/".$fila['Imagen']; 
                                }else{ 
                                    $imagenFile="./assets/img-products/default.png"; 
                                }
                                echo '<br>
                                <a href="product.php" class="btn btn-lg btn-white btn-raised btn-block"><i class="fa fa-mail-reply"></i>&nbsp;&nbsp;Regresar a la tienda</a>
                            </div>


                            <div class="col-xs-12 col-sm-6">
                                <br><br><br>
                                <img class="img-responsive" src="'.$imagenFile.'">
                            </div>';
                    }
                ?>
            </div>
            <div class="container">
                <h3 class="text-center">Reseñas del producto</h3>
                    <?php
                    $productorev= ejecutarSQL::consultar("SELECT reseñas.reseña,cliente.Nombre FROM reseñas INNER JOIN cliente ON cliente.NIT=reseñas.NIT INNER JOIN producto ON producto.id=reseñas.idProd WHERE CodigoProd='".$CodigoProducto."'");
                        echo '<table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Usuario</th>
                                <th>Reseña</th>
                              </tr>
                            </thead>
                            <tbody>';
                            while($filarev=mysqli_fetch_array($productorev, MYSQLI_ASSOC)){
                            echo '<tr>
                              <td>' .$filarev['Nombre']. '</td>
                              <td>' .$filarev['reseña']. '</td>
                            </tr>';
                          }
                        echo '</tbody>
                      </table><br><br>';
                    if($_SESSION['nombreAdmin']!="" || $_SESSION['nombreUser']!=""){
                        $info= ejecutarSQL::consultar("SELECT * FROM producto WHERE CodigoProd='".$CodigoProducto."'");
                        while($filarevs=mysqli_fetch_array($info, MYSQLI_ASSOC)){
                            echo '<form action="process/reseña.php" method="POST" class="FormCatElec" data-form="">
                                <input type="hidden" value="'.$filarevs['id'].'" name="idproducto">
                                <label class="text-center"><h4>Agregue su comentario</h4></label>
                                <div class="form-group">
                                <textarea class="form-control" name="reseña"></textarea>
                                </div>
                                    <button class="btn btn-lg btn-raised btn-success btn-block"><i class="fa fa-comment"></i>&nbsp;&nbsp; Añadir reseña</button>
                                    </form>
                                <div class="ResForm"></div>';
                        } 
                    }else{
                        echo '<p class="text-center"><small>Para dar una reseña debes iniciar sesion</small></p><br>';
                    }
                    ?>
            </div>
        </div>
    </section>

    <?php include './inc/footer.php'; ?>

</body>

</html>
