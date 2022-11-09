<html>
<head>
    <title>Datos Formulario</title>
</head>
<body>
<?php
echo '<h1>Datos sobre ' . $_POST['nombre'] . '</h1>';
echo "Selector Varibles ";
?>
<select name="id_unidad">
<?php

echo "<option value='$id_unidad'>" . $_POST['nombre'] . "</option>";
echo "<option value='$id_unidad'>" . $_POST['apellidouno'] . "</option>";
echo "<option value='$id_unidad'>" . $_POST['apellidodos'] . "</option>";
echo "<option value='$id_unidad'>" . $_POST['deporte'] . "</option>";
echo "<option value='$id_unidad'>" . $_POST['hobby'] . "</option>";

?>

</select>

<pre>
    <strong>DEBUG:</strong>
<?php
print_r($_POST);
?>
</pre>
</body>
</html>