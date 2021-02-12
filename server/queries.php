<?php



// POST
function saveUserCurrent($id, $modalidad, $userEntrada)
{
    date_default_timezone_set('America/Mexico_City');

    $fecha = date("Y-m-d");
    $currentHour = date("H:i:s");



    $today = strtotime($currentHour);
    $horaEntradaDocente = strtotime($userEntrada);
    $estado = '';
    $incidencia = '';
    $nota = '';


    if ($today >= strtotime('-10 minutes', $horaEntradaDocente) && $today <= strtotime('+10 minutes', $horaEntradaDocente)) {
        // echo 'OK de -10 - 10';
        $estado = 'OK';
        $incidencia = 'Ninguna';
        $nota = 'Felicidades';
    } else if ($today > strtotime('+10 minutes', $horaEntradaDocente) && $today <= strtotime('+20 minutes', $horaEntradaDocente)) {
        // echo 'A de 11 - 20';
        $estado = 'A';
        $incidencia = 'Retardo A';
        $nota = '';
    } else if ($today > strtotime('+20 minutes', $horaEntradaDocente) && $today < strtotime('+30 minutes', $horaEntradaDocente)) {
        // echo 'B de 21 - 29';
        $estado = 'B';
        $incidencia = 'Retardo B';
        $nota = '';
    } else if ($today >= strtotime('+30 minutes', $horaEntradaDocente)) {
        // echo 'FALTA MAS DE 30';
        $estado = 'Falta';
        $incidencia = 'Falta';
        $nota = '';
    }




    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");




    $data = mysqli_query(
        $conection,
        "INSERT INTO `current` (`num_tarjeta`, `entrada`, `user_base`,`fecha`,`estado`,`incidencia`,`nota`)
        VALUES ($id, '$currentHour', $modalidad, '$fecha','$estado','$incidencia','$nota' )"
    ) or die("erro al insertar current user");
    return $data;
}

function saveUserCurrentHnoorarios($id, $modalidad)
{
    date_default_timezone_set('America/Mexico_City');

    $fecha = date("Y-m-d");
    $currentHour = date("H:i:s");


    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");

    $data = mysqli_query(
        $conection,
        "INSERT INTO `current` (`num_tarjeta`, `entrada`, `user_base`,`fecha`,`estado`,`incidencia`,`nota`)
        VALUES ($id, '$currentHour', $modalidad, '$fecha','','','' )"
    ) or die("erro al insertar current user");
    return $data;
}


function saveReportUserBase(
    $num_tarjetaB,
    $periodoIB,
    $periodoFB,
    $nombreB,
    $departamentoB,
    $fechaB,
    $H_entradaB,
    $estadoB,
) {
    date_default_timezone_set('America/Mexico_City');

    $currentHour = date("H:i:s");

    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $dataQuery = mysqli_query(
        $conection,
        "INSERT INTO `reporteb` (`num_tarjetaB`, `periodoIB`, `periodoFB`, `nombreB`, `departamentoB`, `fechaB`, `H_entradaB`, `H_salidaB`, `estadoB`) VALUES
        ($num_tarjetaB, '$periodoIB', '$periodoFB', '$nombreB', '$departamentoB', '$fechaB', '$H_entradaB', '$currentHour', '$estadoB')"
    ) or die("erro al insertar reporteB");

    return $dataQuery;
}

function saveReportUserHonorary(
    $num_tarjetaH,
    $periodoIH,
    $periodoFH,
    $nombreH,
    $departamentoH,
    $fechaH,
    $H_entradaH,
    $estadoH,
) {
    date_default_timezone_set('America/Mexico_City');

    $currentHour = date("H:i:s");

    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $dataQuery = mysqli_query(
        $conection,
        "INSERT INTO `reporteh` (`num_tarjetaH`, `periodoIH`, `periodoFH`, `nombreH`, `departamentoH`, `fechaH`, `H_entradaH`, `H_salidaH`, `estadoH`) VALUES
        ($num_tarjetaH, '$periodoIH', '$periodoFH', '$nombreH', '$departamentoH', '$fechaH', '$H_entradaH', '$currentHour', '$estadoH')"
    ) or die("erro al insertar reporteH");

    return $dataQuery;
}


function saveIncidenceUserBase(
    $num_tarjetaB,
    $periodoIB,
    $periodoFB,
    $nombreB,
    $departamentoB,
    $fechaB,
    $H_entradaB,
    $estadoB,
    $incidenciaB,
    $notaB
) {
    date_default_timezone_set('America/Mexico_City');

    $currentHour = date("H:i:s");

    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $dataQuery = mysqli_query(
        $conection,
        "INSERT INTO `incidenciab` (`num_tarjetaB`, `periodoIB`, `periodoFB`, `nombreB`, `departamentoB`, `fechaB`, `H_entradaB`, `H_salidaB`, `estadoB`, `incidenciaB`, `notaB`) VALUES
        ($num_tarjetaB, '$periodoIB', '$periodoFB', '$nombreB', '$departamentoB', '$fechaB', '$H_entradaB', '$currentHour', '$estadoB', '$incidenciaB', '$notaB')"

    ) or die("erro al insertar incidenciaB");

    return $dataQuery;
}

function saveIncidenceUserHonorary(
    $num_tarjetaH,
    $periodoIH,
    $periodoFH,
    $nombreH,
    $departamentoH,
    $fechaH,
    $H_entradaH,
    $estadoH,
    $incidenciaH,
    $notaH
) {
    date_default_timezone_set('America/Mexico_City');

    $currentHour = date("H:i:s");

    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $dataQuery = mysqli_query(
        $conection,
        "INSERT INTO `incidenciah` (`num_tarjetaH`, `periodoIH`, `periodoFH`, `nombreH`, `departamentoH`, `fechaH`, `H_entradaH`, `H_salidaH`, `estadoH`, `incidenciaH`, `notaH`) VALUES
        ($num_tarjetaH, '$periodoIH', '$periodoFH', '$nombreH', '$departamentoH', '$fechaH', '$H_entradaH', '$currentHour', '$estadoH', '$incidenciaH', '$notaH')"

    ) or die("erro al insertar incidenciaH");

    return $dataQuery;
}




// GETS
function getPhotos()
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "SELECT * FROM usuarios_docentesb") or die("erro al consultr la tabal");
    return $data;
}

function getPhotosH()
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "SELECT * FROM usuarios_docentesh") or die("erro al consultr la tabal");
    return $data;
}

function getUserBase($id)
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "SELECT * FROM usuarios_docentesb WHERE num_tarjetaB='" . $id . "'") or die("erro al consultr la tabal");
    return $data;
}

function getUserDetected($id)
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "SELECT *, 'base' as modalidad FROM usuarios_docentesb WHERE num_tarjetaB='" . $id . "'") or die("erro al consultr la tabal");

    if (mysqli_num_rows($data) == 0) {
        $datah = mysqli_query($conection, "SELECT *, 'honorarios' as modalidad FROM usuarios_docentesh WHERE num_tarjetaH='" . $id . "'") or die("erro al consultr la tabal");
        return $datah;
    } else {
        return $data;
    }
}


function getUserHonorary($id)
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "SELECT * FROM usuarios_docentesh WHERE num_tarjetaH='" . $id . "'") or die("erro al consultr la tabal");
    return $data;
}

function getUserCurrent($id)
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "SELECT * FROM current WHERE num_tarjeta='" . $id . "'") or die("erro al consultr la tabal");
    return $data;
}



// DETELE
function deleteUserCurrent($id)
{
    include('./conection.php');
    mysqli_select_db($conection, $db) or die("error al conectar base de datos");
    $data = mysqli_query($conection, "DELETE FROM current WHERE num_tarjeta='" . $id . "'") or die("erro al consultr la tabal");
    return $data;
}
