<?php
include('./queries.php');

// date_default_timezone_set('America/Mexico_City');
// $hoy = date("H:i:s");
// echo $hoy;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $_POST = json_decode(file_get_contents('php://input'), true);
        $type_request = $_POST['type'];


        // Guarda al docente en la tabla current
        if ($type_request == "save_user_current") {
            $id = $_POST['id'];
            $modalidad = $_POST['modalidad'];
            $userEntrada = $_POST['userEntrada'];

            // echo $modalidad;
            $data = saveUserCurrent($id, $modalidad, $userEntrada);

            // añadir la de incidencia
            echo $data;
        }

        // Guarda al docente de base
        else if ($type_request == "save_user_currenth") {
            $id = $_POST['id'];
            $modalidad = $_POST['modalidad'];
            // echo $modalidad;
            $data = saveUserCurrentHnoorarios($id, $modalidad);

            // añadir la de incidencia
            echo $data;
        }



        // Guarda el reporte del docente de base
        else if ($type_request == "save_reporte_user_b") {
            // $dataPost = $_POST['data'];

            $num_tarjetaB = $_POST['num_tarjetaB'];
            $periodoIB = $_POST['periodoIB'];
            $periodoFB = $_POST['periodoFB'];
            $nombreB = $_POST['nombreB'];
            $departamentoB = $_POST['departamentoB'];
            $fechaB = $_POST['fechaB'];
            $H_entradaB = $_POST['H_entradaB'];
            $estadoB = $_POST['estadoB'];
            $incidenciaB = $_POST['estadoB'];
            $notaB = $_POST['estadoB'];

            $dataReport = saveReportUserBase(
                $num_tarjetaB,
                $periodoIB,
                $periodoFB,
                $nombreB,
                $departamentoB,
                $fechaB,
                $H_entradaB,
                $estadoB
            );
            echo $dataReport;
        }

        // Guarda el reporte del docente de honorarios
        else if ($type_request == "save_reporte_user_h") {
            $num_tarjetaH = $_POST['num_tarjetaH'];
            $periodoIH = $_POST['periodoIH'];
            $periodoFH = $_POST['periodoFH'];
            $nombreH = $_POST['nombreH'];
            $departamentoH = $_POST['departamentoH'];
            $fechaH = $_POST['fechaH'];
            $H_entradaH = $_POST['H_entradaH'];
            $estadoH = $_POST['estadoH'];
            $incidenciaH = $_POST['estadoH'];
            $notaH = $_POST['estadoH'];

            $dataReport = saveReportUserHonorary(
                $num_tarjetaH,
                $periodoIH,
                $periodoFH,
                $nombreH,
                $departamentoH,
                $fechaH,
                $H_entradaH,
                $estadoH
            );
            echo $dataReport;
        }

        // Guarda la incidencia del docente de base
        else if ($type_request == "save_incidencia_user_b") {
            $num_tarjetaB = $_POST['num_tarjetaB'];
            $periodoIB = $_POST['periodoIB'];
            $periodoFB = $_POST['periodoFB'];
            $nombreB = $_POST['nombreB'];
            $departamentoB = $_POST['departamentoB'];
            $fechaB = $_POST['fechaB'];
            $H_entradaB = $_POST['H_entradaB'];
            $estadoB = $_POST['estadoB'];
            $incidenciaB = $_POST['incidenciaB'];
            $notaB = $_POST['notaB'];


            $dataIncidence = saveIncidenceUserBase(
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
            );
            echo $dataIncidence;
        }

        // Guarda la incidencia del docente de honorarios
        else if ($type_request == "save_incidencia_user_h") {
            $num_tarjetaH = $_POST['num_tarjetaH'];
            $periodoIH = $_POST['periodoIH'];
            $periodoFH = $_POST['periodoFH'];
            $nombreH = $_POST['nombreH'];
            $departamentoH = $_POST['departamentoH'];
            $fechaH = $_POST['fechaH'];
            $H_entradaH = $_POST['H_entradaH'];
            $estadoH = $_POST['estadoH'];
            $incidenciaH = $_POST['incidenciaH'];
            $notaH = $_POST['notaH'];


            $dataIncidence = saveIncidenceUserHonorary(
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
            );
            echo $dataIncidence;
        } else
            echo "---- not found POST -----";

        break;
    case 'GET':
        $type_request = $_GET['type'];

        // Obtine las fotos de todos
        if ($type_request == "get_photos") {
            $arr = array();
            $dataB = getPhotos();
            $dataH = getPhotosH();

            while ($row = mysqli_fetch_assoc($dataB)) {
                $arr[] = $row['fotoB'];
            };
            while ($row = mysqli_fetch_assoc($dataH)) {
                $arr[] = $row['fotoH'];
            };
            echo json_encode($arr);
        } else if ($type_request == "get_user_detected") {
            $user_id = $_GET['id'];

            $arr = array();
            $data = getUserDetected($user_id);
            while ($row = mysqli_fetch_assoc($data)) {
                $arr[] = $row;
                // $arr['users'][] = $row;
            };
            echo json_encode($arr);
        }
        // Obtinene datos de un docente de base
        else if ($type_request == "get_user_b") {
            $arr = array();
            $data = getUserBase('45685441');
            while ($row = mysqli_fetch_assoc($data)) {
                $arr[] = $row;
                // $arr['users'][] = $row;
            };
            echo json_encode($arr);
        }

        // Obtinene datos de un docente de honorarios
        else if ($type_request == "get_user_h") {
            $arr = array();
            $data = getUserHonorary('1545');
            while ($row = mysqli_fetch_assoc($data)) {
                $arr[] = $row;
                // $arr['users'][] = $row;
            };
            echo json_encode($arr);
        }


        // Obtinene datos de un usuario si esta en la tabla current
        else if ($type_request == "get_user_current") {
            $user_id = $_GET['id'];

            $arr = array();
            $data = getUserCurrent($user_id);
            while ($row = mysqli_fetch_assoc($data)) {
                $arr[] = $row;
            };
            echo json_encode($arr);
        } else
            echo "---- not found GET -----";


        break;
    case 'DELETE':
        // Elimina el registro de la tabla current cuando el usuario hace la salida
        $type_request = $_GET['type'];
        $id = $_GET['id'];
        if ($type_request == "delete_user_current") {
            $data = deleteUserCurrent($id);
            echo $data;
        }
        // echo 'delete------ ' . $type_request;
        break;
};











// http://localhost/facial/index.php


// incidenciab
// incidenciah

// reporteb
// reporteh

// usuarios_docentesb carbon php
// usuarios_docentesh





// aldo();
