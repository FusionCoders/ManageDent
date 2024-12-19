<?php

try{
    $db = new PDO('sqlite:../db/db.sqlite');
    $db ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}catch (PDOException $e){
    echo $e -> getMessage();
    exit();
}

function createSession($role, $email){
    session_start();
    $_SESSION['role'] = $role;
    $_SESSION['email'] = $email;
    global $db;
    if($role == "admin") {
        $stmt = $db -> prepare("SELECT * FROM Dentist WHERE email='$email'");
        $stmt -> execute();
        $dentist = $stmt -> fetch();
        $_SESSION['person_name'] = $dentist['person_name'];
        $_SESSION['birth_date'] = $dentist['birth_date'];
        $_SESSION['tax_id'] = $dentist['tax_id'];
        $_SESSION['phone_number'] = $dentist['phone_number'];
        $_SESSION['photo'] = $dentist['photo'];
        $_SESSION['nameWithoutPrefix'] = strstr($dentist['person_name'], ' ');
    } elseif($role == "dentist") {
        $stmt = $db -> prepare("SELECT * FROM Dentist WHERE email='$email'");
        $stmt -> execute();
        $dentist = $stmt -> fetch();
        $_SESSION['id'] = $dentist['id'];
        $_SESSION['person_name'] = $dentist['person_name'];
        $_SESSION['birth_date'] = $dentist['birth_date'];
        $_SESSION['tax_id'] = $dentist['tax_id'];
        $_SESSION['phone_number'] = $dentist['phone_number'];
        $_SESSION['photo'] = $dentist['photo'];
        $_SESSION['prefix'] = strstr($dentist['person_name'], ' ', true);
        $spacePosition = strpos($dentist['person_name'], ' ');
        $nameWithoutPrefix = substr($dentist['person_name'], $spacePosition + 1);
        $_SESSION['nameWithoutPrefix'] = $nameWithoutPrefix; 
        $_SESSION['schedule1_id'] = $dentist['schedule1_id'];
        $_SESSION['schedule2_id'] = $dentist['schedule2_id'];
        $_SESSION['schedule3_id'] = $dentist['schedule3_id'];
        $_SESSION['schedule4_id'] = $dentist['schedule4_id'];
        $_SESSION['schedule5_id'] = $dentist['schedule5_id'];
    } elseif($role == "assistant") {
        $stmt = $db -> prepare("SELECT * FROM Assistant WHERE email='$email'");
        $stmt -> execute();
        $assistant = $stmt -> fetch();
        $_SESSION['id'] = $assistant['id'];
        $_SESSION['person_name'] = $assistant['person_name'];
        $_SESSION['birth_date'] = $assistant['birth_date'];
        $_SESSION['tax_id'] = $assistant['tax_id'];
        $_SESSION['photo'] = $assistant['photo'];
        $_SESSION['phone_number'] = $assistant['phone_number'];
        $spacePosition = strpos($assistant['person_name'], ' ');
        $nameWithoutPrefix = substr($assistant['person_name'], $spacePosition + 1);
        $_SESSION['nameWithoutPrefix'] = $nameWithoutPrefix; 
    }
}


function recoverHashPasswordAssistant($email){
    global $db;
    $stmt = $db -> prepare ('SELECT assistant_password FROM Assistant WHERE email =?');
    $stmt -> execute(array($email));
    
    $row = $stmt -> fetch();
    
    if ($row){
        return $row['assistant_password'];
    }
}

function recoverHashPasswordDentist($email){
    global $db;
    $stmt = $db -> prepare ('SELECT dentist_password FROM Dentist WHERE email = ?');
    $stmt -> execute(array($email));
    
    while ($row = $stmt -> fetch()){
        return $row['dentist_password'];
    }
}

function dentistValid($email){
    global $db;
    $stmt = $db->prepare('SELECT active_dentist FROM Dentist WHERE email = ?');
    $stmt->execute(array($email));

    $dentist = $stmt->fetch(PDO::FETCH_ASSOC);
    if($dentist['active_dentist']==1){
        return true;
    }else{
        return false;
    }
}

function assistantValid($email){
    global $db;
    $stmt = $db->prepare('SELECT active_assistant FROM Assistant WHERE email = ?');
    $stmt->execute(array($email));

    $assistant = $stmt->fetch(PDO::FETCH_ASSOC);
    if($assistant['active_assistant']==1){
        return true;
    }else{
        return false;
    }
}

function getPhotosDentists(){
    global $db;
    $stmt = $db->prepare('SELECT id, email, person_name, photo, active_dentist FROM Dentist');
    $stmt->execute();

    $result = array(); // Array para armazenar os resultados
    while ($row = $stmt->fetch()) {
        $dentist = array(
            'active_dentist' => $row['active_dentist'],
            'id' => $row['id'],
            'email' => $row['email'],
            'person_name' => $row['person_name'],
            'photo' => $row['photo']
        );
        $result[] = $dentist; // Adiciona o médico ao array de resultados
    }
    return $result;
}

function getPhotosAssistants(){
    global $db;
    $stmt = $db->prepare('SELECT id, email, person_name, photo, active_assistant FROM Assistant');
    $stmt->execute();

    $result = array(); // Array para armazenar os resultados
    while ($row = $stmt->fetch()) {
        $assistant = array(
            'active_assistant' => $row['active_assistant'],
            'id' => $row['id'],
            'email' => $row['email'],
            'person_name' => $row['person_name'],
            'photo' => $row['photo']
        );
        $result[] = $assistant; // Adiciona o médico ao array de resultados
    }
    return $result;
}

function getPhotosMachines(){
    global $db;
    $stmt = $db->prepare('SELECT model, machine_name, photo, reference_number, active_machine FROM Machine');
    $stmt->execute();

    $result = array(); // Array para armazenar os resultados
    while ($row = $stmt->fetch()) {
        $machine = array(
            'active_machine' => $row['active_machine'],
            'model' => $row['model'],
            'machine_name' => $row['machine_name'],
            'reference_number' => $row['reference_number'],
            'photo' => $row['photo']
        );
        $result[] = $machine; // Adiciona o maquina ao array de resultados
    }
    return $result;
}


//-------------------------------------Atualizar Perfil---------------------------------------
function editPersonalArea($email, $person_name, $birth_date, $tax_id, $phone_number) {
    global $db;
    session_start();
    $role = $_SESSION['role'];
    if($role == "dentist") {
        // Se o nome for vazio não se adiciona o prefixo (para ser detetado na restrição da base de dados)
        if (empty(trim($person_name))) {
            $nameWithPrefix = $person_name;
        } else {
            $nameWithPrefix = $_SESSION['prefix'] . " " . $nome;
        }
        $stmt = $db -> prepare ('UPDATE Dentist SET person_name=?, birth_date=?, tax_id=?, phone_number=? WHERE email = ?');
        $stmt -> execute(array($nameWithPrefix, $birth_date, $tax_id, $phone_number, $email));
        // Destrói a sessão
        $_SESSION = array();
        session_destroy();
        // Abre nova sessão com os valores atualizados
        createSession($role, $email);

    } elseif($role == "assistant") {
        // Se o nome for vazio não se adiciona o prefixo (para ser detetado na restrição da base de dados)
        if (empty(trim($person_name))) {
            $nameWithPrefix = $person_name;
        } else {
            $nameWithPrefix = "Ass." . " " . $person_name;
        }
        $stmt = $db -> prepare ('UPDATE Assistant SET person_name=?, birth_date=?, tax_id=?, phone_number=? WHERE email = ?');
        $stmt -> execute(array($nameWithPrefix, $birth_date, $tax_id, $phone_number, $email));
        // Destrói a sessão
        $_SESSION = array();
        session_destroy();
        // Abre nova sessão com os valores atualizados
        createSession($role, $email);
    }
}

function CheckRepeatedTaxId($tax_id) {
    global $db;
    session_start();

    if ($tax_id == $_SESSION['tax_id']) {
        return false;
    } else {
        // Consulta para verificar a existência do valor 'nif' em todas as tabelas
        $stmt = $db -> prepare('SELECT COUNT(*) AS total FROM 
            (SELECT email FROM Dentist WHERE tax_id = ?
            UNION
            SELECT email FROM Assistant WHERE tax_id = ?
            UNION
            SELECT email FROM Patient WHERE tax_id = ?)');
        $stmt -> execute(array($tax_id,$tax_id,$tax_id));

        $total = 0;
        if ($row = $stmt->fetch()) {
            $total =  $row['total'];
        }
        echo $total;
        if ($total > 0) {
            // O valor 'nif' já existe em pelo menos uma tabela
            return true;
        } else {
            // O valor 'nif' é único em todas as tabelas
            return false;
        }
    }
}

function getSpecialtiesDentist() {
    global $db;
    $dentist_id = $_SESSION['id'];
    $stmt = $db -> prepare('SELECT specialty_name
                         FROM Dentist_Specialty AS EM
                         JOIN Specialty AS E ON EM.specialty_id = E.id
                         WHERE EM.dentist_id = ?');
    $stmt -> execute(array($dentist_id));

    $result = array();
    while ($row = $stmt->fetch()) {
        $result[] = $row['specialty_name'];
    }
    return $result;
}

function getSpecialtiesDentist2($dentist_id) {
    global $db;
    $stmt = $db -> prepare('SELECT E.id AS specialty_id, specialty_name
                         FROM Dentist_Specialty AS EM
                         JOIN Specialty AS E ON EM.specialty_id = E.id
                         WHERE EM.dentist_id = ?');
    $stmt -> execute(array($dentist_id));

    $result = array();
    while ($row = $stmt->fetch()) {
        $specialty = array(
            'specialty_id' => $row['specialty_id'],
            'specialty_name' => $row['specialty_name']
        );
        $result[] = $specialty;
    }
    return $result;
}

function getSpecialties() {
    global $db;
    $dentist_id = $_SESSION['id'];
    $stmt = $db -> prepare('SELECT specialty_name
                         FROM Specialty
                         WHERE id NOT IN (
                            SELECT specialty_id
                            FROM Dentist_Specialty
                            WHERE dentist_id = ?)');
    $stmt -> execute(array($dentist_id));

    $specialties = array();
    while ($row = $stmt->fetch()) {
        $specialties[] = $row['specialty_name'];
    }
    return $specialties;
}

function removeSpecialty($specialty) {
    global $db;
    session_start();
    $dentist_id = $_SESSION['id'];

    // Obter ID da especialidade
    $stmt = $db -> prepare('SELECT id FROM Specialty WHERE specialty_name = ?');
    $stmt -> execute(array($specialty));
    $row = $stmt -> fetch();
    $specialty_id = $row['id'];

    // Remover especialidade com base no ID da especialidade e ID do médico
    $stmt = $db -> prepare('DELETE FROM Dentist_Specialty WHERE dentist_id = ? AND specialty_id = ?');
    $stmt -> execute(array($dentist_id, $specialty_id));
}

function addSpecialty($specialty) {
    global $db;
    session_start();
    $dentist_id = $_SESSION['id'];

    // Obter ID da especialidade
    $stmt = $db -> prepare('SELECT id FROM Specialty WHERE specialty_name = ?');
    $stmt -> execute(array($specialty));
    $row = $stmt -> fetch();
    $specialty_id = $row['id'];

    // Associar especialidade ao médico com base nos IDs
    $stmt = $db -> prepare('INSERT INTO Dentist_Specialty VALUES (?,?)');
    $stmt -> execute(array($specialty_id, $dentist_id));
}

//---------------------------------------------Relatórios----------------------------------------------
function getReports($date_appointment) {
    global $db;
    $dentist_id = $_SESSION['id'];
    $stmt = $db -> prepare('SELECT *
                         FROM Appointment as C
                         JOIN Report as R ON C.id = R.appointment_id
                         WHERE dentist_id = ? AND C.date_appointment = ?');
    $stmt -> execute(array($dentist_id, $date_appointment));
    
    $reports = array();
    while ($row = $stmt->fetch()) {
        $report = array(
            'report_id' => $row['report_id'],
            'appointment_id' => $row['appointment_id'],
            'patient_id' => $row['patient_id'],
            'assistant_id' => $row['assistant_id'],
            'medical_procedure_id' => $row['medical_procedure_id'],
            'observations' => $row['observations'],
            'dentist_id' => $row['dentist_id']
        );
        $reports[] = $report;
    }
    return $reports;
}

function getReport($appointment_id) {
    global $db;
    $stmt = $db -> prepare('SELECT * FROM Report WHERE appointment_id=?');
    $stmt -> execute(array($appointment_id));

    $row = $stmt->fetch();

    return $row;
}

function getAllReports($date_appointment) {
    global $db;
    $dentist_id = $_SESSION['id'];
    $stmt = $db -> prepare('SELECT *
                         FROM Appointment as C
                         JOIN Report as R ON C.id = R.appointment_id
                         WHERE dentist_id != ? AND C.date_appointment = ?');
    $stmt -> execute(array($dentist_id, $date_appointment));
    
    $all_reports = array();
    while ($row = $stmt->fetch()) {
        $report = array(
            'report_id' => $row['report_id'],
            'appointment_id' => $row['appointment_id'],
            'patient_id' => $row['patient_id'],
            'assistant_id' => $row['assistant_id'],
            'medical_procedure_id' => $row['medical_procedure_id'],
            'observations' => $row['observations'],
            'dentist_id' => $row['dentist_id']
        );
        $all_reports[] = $report;
    }
    return $all_reports;
}

function getProcedure($procedure_id) {
    global $db;
    $stmt = $db -> prepare('SELECT * FROM Medical_Procedure WHERE id=?');
    $stmt -> execute(array($procedure_id));

    while ($row = $stmt->fetch()) {
        $procedure = array(
            'id' => $row['id'],
            'name' => $row['medical_procedure_name'],
            'specialty_id' => $row['specialty_id']
        );
    }
    return $procedure;
}

function getDentistAppointment($appointment_id) {
    global $db;
    $stmt = $db -> prepare('SELECT dentist_id FROM Appointment WHERE id=?');
    $stmt -> execute(array($appointment_id));

    $row = $stmt->fetch();
    $dentist_id = $row['dentist_id'];
   
    return $dentist_id;
}

function getAllProcedures() {
    global $db;
    $stmt = $db -> prepare('SELECT * FROM Medical_Procedure');
    $stmt -> execute();

    $all_procedures = array();
    while ($row = $stmt->fetch()) {
        $procedure = array(
            'id' => $row['id'],
            'name' => $row['medical_procedure_name'],
            'specialty_procedure' => $row['specialty_id']
        );
        $all_procedures[] = $procedure;
    }
    return $all_procedures;
}

function getPatientName($patient_id) {
    global $db;
    $stmt = $db -> prepare('SELECT person_name FROM Patient WHERE id=?');
    $stmt -> execute(array($patient_id));

    $row = $stmt -> fetch();
    $patient_name = $row['person_name']; 
    
    return $patient_name;
}

function getDentistName($dentist_id) {
    global $db;
    $stmt = $db -> prepare('SELECT person_name FROM Dentist WHERE id=?');
    $stmt -> execute(array($dentist_id));

    $row = $stmt -> fetch();
    $dentist_name = $row['person_name']; 
    
    return $dentist_name;
}

function getAssistantName($assistant_id) {
    global $db;
    $stmt = $db -> prepare('SELECT person_name FROM Assistant WHERE id=?');
    $stmt -> execute(array($assistant_id));

    $row = $stmt -> fetch();
    $assistant_name = $row['person_name']; 
    
    return $assistant_name;
}

function getProcedureName($procedure_id) {
    global $db;
    $stmt = $db -> prepare('SELECT medical_procedure_name FROM Medical_Procedure WHERE id=?');
    $stmt -> execute(array($procedure_id));

    $row = $stmt -> fetch();
    $medical_procedure_name = $row['medical_procedure_name']; 
    
    return $medical_procedure_name;
}

function getMachinesName($id_appointment) {
    global $db;
    $stmt = $db -> prepare('SELECT machine_name, model 
                            FROM Machine_Appointment AS MC
                            JOIN Machine AS M ON MC.machine_id = M.reference_number
                            WHERE appointment_id=?');
    $stmt -> execute(array($id_appointment));

    $name_machines = array();
    while ($row = $stmt->fetch()) {
        $machine = array(
            'machine_name' => $row['machine_name'],
            'model' => $row['model']
        );
        $name_machines[] = $machine;
    }
    return $name_machines;
}

function getHoursAppointment($id_appointment) {
    global $db;
    $stmt = $db -> prepare('SELECT start_time, end_time
                            FROM Appointment AS C
                            JOIN Schedule as H ON C.schedule_id = H.id
                            WHERE C.id =?');
    $stmt -> execute(array($id_appointment));
    $row = $stmt->fetch(); 
    $hours_appointment = array(
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time']);
    return $hours_appointment;
}

function addReport($appointment_id, $procedure_id, $observations) {
    global $db;
    $stmt = $db -> prepare('INSERT INTO Report (observations, appointment_id, medical_procedure_id) VALUES (?,?,?)');
    $stmt -> execute(array($observations, $appointment_id, $procedure_id));
}

function editReport($report_id, $observations) {
    global $db;
    $stmt = $db -> prepare('UPDATE Report SET observations=? 
                            WHERE report_id=?');
    $stmt -> execute(array($observations, $report_id));
}

function deleteReport($report_id) {
    global $db;
    
    $stmt = $db -> prepare('DELETE FROM Report WHERE report_id=?');
    $stmt -> execute(array($report_id));
}

//---------------------------------------------Consultas----------------------------------------------
function getAppointment($appointment_id) {
    global $db;
    $stmt = $db -> prepare('SELECT * FROM Appointment WHERE id=?');
    $stmt -> execute(array($appointment_id));

    while ($row = $stmt->fetch()) {
        $appointment = array(
            'date_appointment' => $row['date_appointment'],
            'patient_id' => $row['patient_id'],
            'assistant_id' => $row['assistant_id'],
            'schedule_id' => $row['schedule_id']
        );
    }
    return $appointment;

}

function getAppointments($date_appointment, $role) {
    global $db;
    $professional_id = $_SESSION['id'];
    if ($role == "dentist"){
        $stmt = $db -> prepare('SELECT C.id AS id, 
                            C.patient_id AS patient_id, 
                            C.assistant_id AS assistant_id, 
                            C.schedule_id as schedule_id
                                FROM Appointment AS C 
                                JOIN Schedule AS H ON C.schedule_id=H.id
                                WHERE dentist_id = ? AND date_appointment = ? ORDER BY start_time');
    } else if ($role == "assistant"){
        $stmt = $db -> prepare('SELECT C.id AS id, 
                            C.patient_id AS patient_id, 
                            C.dentist_id AS dentist_id, 
                            C.schedule_id as schedule_id
                                FROM Appointment AS C 
                                JOIN Schedule AS H ON C.schedule_id=H.id
                                WHERE assistant_id = ? AND date_appointment = ? ORDER BY start_time');
    }
    $stmt -> execute(array($professional_id, $date_appointment));
    
    $appointments = array();
    if ($role == "dentist"){
        while ($row = $stmt->fetch()) {
            $appointment = array(
                'appointment_id' => $row['id'],
                'patient_id' => $row['patient_id'],
                'assistant_id' => $row['assistant_id'],
                'schedule_id' => $row['schedule_id']
            );
            $appointments[] = $appointment;
        }
    } else if ($role == "assistant"){
        while ($row = $stmt->fetch()) {
            $appointment = array(
                'appointment_id' => $row['id'],
                'patient_id' => $row['patient_id'],
                'dentist_id' => $row['dentist_id'],
                'schedule_id' => $row['schedule_id']
            );
            $appointments[] = $appointment;
        }
    }
    return $appointments;
}

function getAllAppointments($date_appointment) {
    global $db;
    session_start();
    $professional_id = $_SESSION['id'];
    $role = $_SESSION['role'];
    if ($role == "dentist"){
        $stmt = $db -> prepare('SELECT *
                            FROM Appointment
                            WHERE dentist_id != ? AND date_appointment = ?');
    } elseif ($role == "assistant"){
        $stmt = $db -> prepare('SELECT *
                         FROM Appointment
                         WHERE assistant_id != ? AND date_appointment = ?');
    }
    $stmt -> execute(array($professional_id, $date_appointment));
    $all_appointments = array();
    while ($row = $stmt->fetch()) {
        $appointment = array(
            'appointment_id' => $row['id'],
            'dentist_id' => $row['dentist_id'],
            'patient_id' => $row['patient_id'],
            'assistant_id' => $row['assistant_id'],
            'schedule_id' => $row['schedule_id']
        );
        $all_appointments[] = $appointment;
    }
    return $all_appointments;
}

function getAllAssistants() {
    global $db;
    $stmt = $db -> prepare('SELECT id, person_name FROM Assistant');
    $stmt -> execute(array());

    $all_assistants = array();
    while ($row = $stmt->fetch()) {
        $assistant = array(
            'person_name' => $row['person_name'],
            'id' => $row['id']
        );
        $all_assistants[] = $assistant;
    }
    return $all_assistants;
}

function getAllDentists() {
    global $db;
    $stmt = $db -> prepare('SELECT id, person_name FROM Dentist');
    $stmt -> execute(array());

    $all_dentists = array();
    while ($row = $stmt->fetch()) {
        $dentist = array(
            'person_name' => $row['person_name'],
            'id' => $row['id']
        );
        $all_dentists[] = $dentist;
    }
    return $all_dentists;
}

function editAppointment($date_appointment, $appointment_id, $patient_id, $professional_id, $start_time, $end_time) {
    global $db;
    session_start();
    $role = $_SESSION['role'];
    $day_week = date('N', strtotime($date_appointment));
    $schedule_id = checkScheduleExistence($day_week, $start_time, $end_time);
    if ($role == "dentist"){
        $stmt = $db -> prepare('UPDATE Appointment SET patient_id=?, assistant_id=?, schedule_id=? 
                                    WHERE id=?');
    } elseif ($role == "assistant"){
        $stmt = $db -> prepare('UPDATE Appointment SET patient_id=?, dentist_id=?, schedule_id=? 
                                    WHERE id=?');
    }
    $stmt -> execute(array($patient_id, $professional_id, $schedule_id, $appointment_id));
}

function checkScheduleExistence($day_week, $start_time, $end_time) {
    global $db;
    $stmt = $db -> prepare('SELECT * FROM Schedule WHERE week_day=? AND start_time=? AND end_time=?');
    $stmt -> execute(array($day_week, $start_time, $end_time));

    if ($row = $stmt->fetch()) {
        $schedule_id =  $row['id'];
        return $schedule_id;
    }else{
        $stmt = $db -> prepare('INSERT INTO Schedule (week_day, start_time, end_time) VALUES (?,?,?)');
        $stmt -> execute(array($day_week, $start_time, $end_time));
        return $db->lastInsertId();
    };
}

function availabilityProfessional($date_appointment, $professional_id, $start_time, $end_time, $appointment_id){
    session_start();
    $role = $_SESSION['role'];
    if ($role == "dentist"){
        $var = availabilityAssistant($date_appointment, $professional_id, $start_time, $end_time, $appointment_id);
    } elseif ($role == "assistant"){
        $var = availabilityDentist($date_appointment, $professional_id, $start_time, $end_time, $appointment_id);
    }
    return $var;
}

function availabilityAssistant($date_appointment, $assistant_id, $start_time, $end_time, $appointment_id) {
    global $db;
    if($appointment_id==NULL) {
        $stmt = $db -> prepare('SELECT start_time, end_time 
                            FROM Appointment AS C 
                            JOIN Schedule AS H ON C.schedule_id=H.id
                            WHERE C.assistant_id=? AND C.date_appointment=?');
        $stmt -> execute(array($assistant_id, $date_appointment));
    } else {
        $stmt = $db -> prepare('SELECT start_time, end_time 
                                FROM Appointment AS C 
                                JOIN Schedule AS H ON C.schedule_id=H.id
                                WHERE C.assistant_id=? AND C.date_appointment=? AND C.id<>?');
        $stmt -> execute(array($assistant_id, $date_appointment, $appointment_id));
    }

    $all_hours = array();
    while ($row = $stmt->fetch()) {
        $hours = array(
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time']
        );
        $all_hours[] = $hours;
    }
    $var=0;
    foreach ($all_hours as $hours) {
        $start = $hours['start_time'];
        $end = $hours['end_time'];
        if (!(($start<$start_time && $end<=$start_time) ||
            ($start>=$end_time && $end>$end_time))) {
            $var+=1;
        }
    }
    return $var;
}

function availabilityDentist($date_appointment, $dentist_id,  $start_time, $end_time, $appointment_id) {
    global $db;
    if($appointment_id==NULL) {
        $stmt = $db -> prepare('SELECT start_time, end_time 
                            FROM Appointment AS C 
                            JOIN Schedule AS H ON C.schedule_id=H.id
                            WHERE C.dentist_id=? AND C.date_appointment=?');
        $stmt -> execute(array($dentist_id, $date_appointment));
    } else {
        $stmt = $db -> prepare('SELECT start_time, end_time 
                            FROM Appointment AS C 
                            JOIN Schedule AS H ON C.schedule_id=H.id
                            WHERE C.dentist_id=? AND C.date_appointment=? AND C.id<>?');
        $stmt -> execute(array($dentist_id, $date_appointment, $appointment_id));
    }

    $all_hours = array();
    while ($row = $stmt->fetch()) {
        $hours = array(
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time']
        );
        $all_hours[] = $hours;
    }
    $var=0;
    foreach ($all_hours as $hours) {
        $start = $hours['start_time'];
        $end = $hours['end_time'];
        if (!(($start<$start_time && $end<=$start_time) ||
            ($start>=$end_time && $end>$end_time))) {
            $var+=1;
        }
    }
    return $var;
}

function availabilityPatient($date_appointment, $patient_id,  $start_time, $end_time, $appointment_id) {
    global $db;
    if($appointment_id==NULL) {
        $stmt = $db -> prepare('SELECT start_time, end_time 
                            FROM Appointment AS C 
                            JOIN Schedule AS H ON C.schedule_id=H.id
                            WHERE C.patient_id=? AND C.date_appointment=?');
        $stmt -> execute(array($patient_id, $date_appointment));
    } else {
        $stmt = $db -> prepare('SELECT start_time, end_time 
                            FROM Appointment AS C 
                            JOIN Schedule AS H ON C.schedule_id=H.id
                            WHERE C.patient_id=? AND C.date_appointment=? AND C.id<>?');
        $stmt -> execute(array($patient_id, $date_appointment, $appointment_id));
    }

    $all_hours = array();
    while ($row = $stmt->fetch()) {
        $hours = array(
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time']
        );
        $all_hours[] = $hours;
    }
    $var=0;
    foreach ($all_hours as $hours) {
        $start = $hours['start_time'];
        $end = $hours['end_time'];
        if (!(($start<$start_time && $end<=$start_time) ||
            ($start>=$end_time && $end>$end_time))) {
            $var+=1;
        }
    }
    return $var;
}

function checkIfDentistWorks($dentist_id, $date_appointment, $start_time, $end_time) {
    global $db;
    $day_week = date('N', strtotime($date_appointment));
    $all_schedules = array();
    $schedule_columns = array('schedule1_id', 'schedule2_id', 'schedule3_id', 'schedule4_id', 'schedule5_id');
    for($i=1; $i<6; $i++) {
        $schedule_column = $schedule_columns[$i - 1];
        $stmt = $db -> prepare("SELECT * 
                        FROM Dentist AS M
                        JOIN Schedule AS H ON M.'$schedule_column'=H.id
                        WHERE M.id=?");
        $stmt -> execute(array($dentist_id));
        while($row = $stmt->fetch()) {
            $schedule = array(
                'dayWeek' => $row['week_day'],
                'startTime' => $row['start_time'],
                'endTime' => $row['end_time']
            );
            $all_schedules[] = $schedule;
        }
    }
    $var=0;
    $aux=0;
    foreach($all_schedules as $schedule) {
        if($schedule['dayWeek']==$day_week) {
            $aux+=1;
        }
    }
    if($aux==0) {
        $var=1;
        return $var;
    } elseif ($aux!=0) {
        foreach($all_schedules as $schedule) {
            if(($schedule['dayWeek']==$day_week 
                && ($schedule['startTime']>$start_time
                || $schedule['endTime']<$end_time))) {
            $var+=1;
            }
        }
    }
    return $var;
}

function verificarSeAssistenteTrabalha($assistente_id, $data_consulta, $hora_inicio, $hora_fim) {
    global $db;
    $dia_semana = date('N', strtotime($data_consulta));
    $todos_horarios = array();
    $horario_colunas = array('horario1_id', 'horario2_id', 'horario3_id', 'horario4_id', 'horario5_id');
    for($i=1; $i<6; $i++) {
        $coluna_horario = $horario_colunas[$i - 1];
        $stmt = $db -> prepare("SELECT * 
                        FROM Assistente AS A
                        JOIN Horario AS H ON A.'$coluna_horario'=H.id
                        WHERE A.id=?");
        $stmt -> execute(array($assistente_id));
        while($row = $stmt->fetch()) {
            $horario = array(
                'diaSemana' => $row['dia_semana'],
                'horaInicio' => $row['hora_inicio'],
                'horaFim' => $row['hora_fim']
            );
            $todos_horarios[] = $horario;
        }
    }
    $var=0;
    $aux=0;
    foreach($todos_horarios as $horario) {
        if($horario['diaSemana']==$dia_semana) {
            $aux+=1;
        }
    }
    if($aux==0) {
        $var=1;
        return $var;
    } elseif ($aux!=0) {
        foreach($todos_horarios as $horario) {
            if(($horario['diaSemana']==$dia_semana 
                && ($horario['horaInicio']>$hora_inicio
                || $horario['horaFim']<$hora_fim))) {
            $var+=1;
            }
        }
    }
    return $var;
}

function removeAppointment($appointment_id) {
    global $db;
    $stmt = $db -> prepare('DELETE FROM Appointment WHERE id=?');
    $stmt -> execute(array($appointment_id));
}

function addAppointment($date_appointment, $dentist_id, $patient_id, $assistant_id, $start_time, $end_time){
    global $db;
    $day_week = date('N', strtotime($date_appointment));
    $schedule_id = checkScheduleExistence($day_week, $start_time, $end_time);
    $stmt = $db -> prepare('INSERT INTO Appointment (date_appointment, dentist_id, 
                            patient_id, assistant_id, schedule_id) VALUES(?,?,?,?,?)');
    $stmt -> execute(array($date_appointment, $dentist_id, $patient_id, $assistant_id, $schedule_id));
}

//----------------------------------------------Máquinas----------------------------------------------

function getDataMachine($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM Machine WHERE reference_number = ?');
    $stmt->execute(array($id));

    $machine = $stmt->fetch(PDO::FETCH_ASSOC);
    return $machine;
}

function getBrands(){
    global $db;
    $stmt = $db->prepare('SELECT * FROM Brand ORDER BY brand_name ASC');
    $stmt->execute();

    $result = array(); // Array para armazenar os resultados
    while ($row = $stmt->fetch()) {
        $brand = array(
            'id' => $row['id'],
            'brand_name' => $row['brand_name'],
        );
        $result[] = $brand; // Adiciona a marca ao array de resultados
    }
    return $result;
}

function saveBrand($newBrand){
    global $db;
    // Inserir nova marca
    $stmt = $db->prepare('INSERT INTO Brand (brand_name) VALUES (?)');
    $stmt->execute(array($newBrand));
    // Retornar o ID da última marca inserida
    return $db->lastInsertId();
}

function getCurrentBrand(){
    global $db;
    $stmt = $db->prepare('SELECT brand_name FROM Brand WHERE id = ?');
    $stmt->execute(array($_SESSION['brand_id']));
    $brand = $stmt->fetch(PDO::FETCH_ASSOC);
    return $brand;
}

function createMachine($reference_number, $name, $model, $brand, $active_machine, $photo){
    global $db;
    $stmt = $db -> prepare('INSERT INTO Machine VALUES (?,?,?,?,?,?)');
    $stmt -> execute(array($reference_number, $name, $model, $photo, $active_machine, $brand));
}

function editMachine($reference_number, $name, $model, $brand, $photo, $active_machine){
    global $db;
    $stmt = $db -> prepare ('UPDATE Machine SET machine_name=?, model=?, photo=?, active_machine=?, brand_id=? WHERE reference_number = ?');
    $stmt -> execute(array($name, $model, $photo, $active_machine, $brand, $reference_number));
}

function removeAddMachine($reference_number){
    global $db;
    session_start();
    $stmt = $db -> prepare ('UPDATE Machine SET active_machine=? WHERE reference_number = ?');
    if ($_SESSION['active_machine']==0){
        $stmt -> execute(array(1, $reference_number));
    }else{
        $stmt -> execute(array(0, $reference_number));
    }
}

//----------------------------------------------Médico--------------------------------------------------------

function getDataDentist($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM Dentist WHERE id = ?');
    $stmt->execute(array($id));

    $dentist = $stmt->fetch(PDO::FETCH_ASSOC);
    return $dentist;
}

function getDentistSchedule($selectedDate, $role) {
    global $db;
    $dentist_id = $_SESSION['id'];
    $dayWeek = date('N', strtotime($selectedDate));

    if ($role == "dentist"){
        $stmt = $db->prepare('SELECT start_time, end_time 
                          FROM Schedule JOIN Dentist 
                          ON (Dentist.schedule1_id = Schedule.id 
                          OR Dentist.schedule2_id = Schedule.id
                          OR Dentist.schedule3_id = Schedule.id
                          OR Dentist.schedule4_id = Schedule.id
                          OR Dentist.schedule5_id = Schedule.id)
                          WHERE Dentist.id = ? AND Schedule.week_day = ?');

    } else if ($role == "assistant"){
        $stmt = $db->prepare('SELECT start_time, end_time 
                          FROM Schedule JOIN Assistant 
                          ON (Assistant.schedule1_id = Schedule.id 
                          OR Assistant.schedule2_id = Schedule.id
                          OR Assistant.schedule3_id = Schedule.id
                          OR Assistant.schedule4_id = Schedule.id
                          OR Assistant.schedule5_id = Schedule.id)
                          WHERE Assistant.id = ? AND Schedule.week_day = ?');

    }

    $stmt->execute(array($dentist_id, $dayWeek));

    $schedule = array();
    while ($row = $stmt -> fetch()){
        
        $schedule = array(
            'start_time' => $row['start_time'], 
            'end_time' => $row ['end_time']
        );
    }
    return $schedule;
}


function getAppointmentsDentist($date_appointment, $role) {
    global $db;
    $dentist_id = $_SESSION['id'];


    if ($role == "dentist"){
        $stmt = $db -> prepare('SELECT start_time, end_time
            FROM Schedule JOIN Appointment
            ON Appointment.schedule_id = Schedule.id
            WHERE dentist_id = ? AND date_appointment = ?');
            
    } else if ($role == "assistant"){
        $stmt = $db -> prepare('SELECT start_time, end_time
                            FROM Schedule JOIN Appointment
                            ON Appointment.schedule_id = Schedule.id
                            WHERE assistant_id = ? AND date_appointment = ?');
    }
    
    $stmt -> execute(array($dentist_id, $date_appointment));
    $appointments = array();
    while ($row = $stmt->fetch()) {
        $appointment = array(
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time']
        );
        $appointments[] = $appointment;
    }
    return $appointments;
}



function getMedicoHorarioConsultas($selectedDate) {
    global $db;
    $medico_id = $_SESSION['id'];
    $diaSemana = date('N', strtotime($selectedDate));
    $stmt = $db->prepare('SELECT hora_inicio, hora_fim 
                          FROM Horario JOIN Medico 
                          ON (Medico.horario1_id = Horario.id 
                          OR Medico.horario2_id = Horario.id
                          OR Medico.horario3_id = Horario.id
                          OR Medico.horario4_id = Horario.id
                          OR Medico.horario5_id = Horario.id)
                          WHERE Medico.id = ? AND Horario.dia_semana = ?');
    $stmt->execute(array($medico_id, $diaSemana));

    $horario = array();
    while ($row = $stmt -> fetch()){
        
        $horario = array(
            'hora_inicio' => $row['hora_inicio'], 
            'hora_fim' => $row ['hora_fim']
        );
    }
    return $horario;
}

function getHoursDentist() {
    global $db;

    $stmt = $db->prepare('SELECT * FROM Schedule WHERE id = ? OR id = ? OR id = ? OR id = ? OR id = ? ORDER BY week_day');
    $stmt->execute(array($_SESSION['schedule1_idDen'], $_SESSION['schedule2_idDen'], $_SESSION['schedule3_idDen'], $_SESSION['schedule4_idDen'], $_SESSION['schedule5_idDen']));

    $result = array(); // Array para armazenar os resultados
    while ($row = $stmt->fetch()) {
        $schedule = array(
            'id' => $row['id'],
            'week_day' => $row['week_day'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
        );
        $result[] = $schedule; // Adiciona o horário ao array de resultados
    }
    return $result;
}

function editDentist($id, $name, $tax_id, $birth_date, $phone_number, $email, $salary, $officeNum, $password, $photo, $active_dentist, $sch1, $sch2, $sch3, $sch4, $sch5){
    global $db;
    $stmt = $db -> prepare ('UPDATE Dentist SET person_name=?, tax_id=?, birth_date=?, phone_number=?, email=?, salary=?, office=?, dentist_password=?, photo=?, active_dentist=?, schedule1_id=?, schedule2_id=?, schedule3_id=?, schedule4_id=?, schedule5_id=? WHERE id = ?');
    $stmt -> execute(array($name, $tax_id, $birth_date, $phone_number, $email, $salary, $officeNum, $password, $photo, $active_dentist, $sch1, $sch2, $sch3, $sch4, $sch5, $id));
}

function checkTaxIdRepeatedDentist($tax_id) {
    global $db;

    if ($tax_id == $_SESSION['tax_idDen']) {
        return false;
    } else {
        // Consulta para verificar a existência do valor 'nif' em todas as tabelas
        $stmt = $db -> prepare('SELECT COUNT(*) AS total FROM 
            (SELECT email FROM Dentist WHERE tax_id = ?
            UNION
            SELECT email FROM Assistant WHERE tax_id = ?
            UNION
            SELECT email FROM Patient WHERE tax_id = ?)');
        $stmt -> execute(array($tax_id,$tax_id,$tax_id));

        $total = 0;
        if ($row = $stmt->fetch()) {
            $total =  $row['total'];
        }
        echo $total;
        if ($total > 0) {
            // O valor 'nif' já existe em pelo menos uma tabela
            return true;
        } else {
            // O valor 'nif' é único em todas as tabelas
            return false;
        }
    }
}

function numberLinesDentist(){
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM Dentist");
    $stmt->execute();

    $total=0;
    if ($row = $stmt->fetch()) {
        $total =  $row['total'];
    }
    return $total;
}

function createDentist($id, $name, $tax_id, $birth_date, $phone_number, $email, $salary, $officeNum, $password, $photo, $active_dentist, $sch1, $sch2, $sch3, $sch4, $sch5){
    global $db;
    $stmt = $db -> prepare('INSERT INTO Dentist VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt -> execute(array($id, $name, $tax_id, $birth_date, $phone_number, $email, $salary, $officeNum, $password, $photo, $active_dentist, $sch1, $sch2, $sch3, $sch4, $sch5));
}

function removeAddDentist(){
    global $db;
    session_start();
    $stmt = $db -> prepare ('UPDATE Dentist SET active_dentist=? WHERE id = ?');
    if ($_SESSION['active_dentist']==0){
        $stmt -> execute(array(1, $_SESSION['idDen']));
    }else{
        $stmt -> execute(array(0, $_SESSION['idDen']));
    }
}

//----------------------------------------------Paciente--------------------------------------------------------

function getDadosPaciente() {
    global $db;
    $stmt = $db->prepare('SELECT id, email, nome FROM Paciente WHERE id = ?');
    $stmt->execute();
    
    $result = array();
    
    while ($row = $stmt->fetch()) {
        $paciente = array(
            'id' => $row['id'],
            'email' => $row['email'],
            'nome' => $row['nome'],
        );
        $result[] = $paciente;
    }
    return $result;
}

function getDadosThisPaciente($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM Paciente WHERE id = ?');
    $stmt->execute(array($id));

    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);
    return $paciente;
}


function getAllPatients() {
    global $db;
    $stmt = $db->prepare('SELECT id, person_name, tax_id, birth_date, phone_number, email FROM Patient');
    $stmt->execute();

    $all_patients = array();
    while ($row = $stmt->fetch()) {
        $patient = array(
            'id' => $row['id'],
            'person_name' => $row['person_name'],
            'tax_id' => $row['tax_id'],
            'birth_date' => $row['birth_date'],
            'phone_number' => $row['phone_number'],
            'email' => $row['email']
        );
        $all_patients[] = $patient;
    }
    return $all_patients;
}

//--------------------------------------------------------Assistente---------------------------------------

function getAssistantData($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM Assistant WHERE id = ?');
    $stmt->execute(array($id));

    $assistant = $stmt->fetch(PDO::FETCH_ASSOC);
    return $assistant;
}

function getHoursAssistant() {
    global $db;

    $stmt = $db->prepare('SELECT * FROM Schedule WHERE id = ? OR id = ? OR id = ? OR id = ? OR id = ? ORDER BY week_day');
    $stmt->execute(array($_SESSION['schedule1_idAss'], $_SESSION['schedule2_idAss'], $_SESSION['schedule3_idAss'], $_SESSION['schedule4_idAss'], $_SESSION['schedule5_idAss']));

    $result = array(); // Array para armazenar os resultados
    while ($row = $stmt->fetch()) {
        $schedule = array(
            'id' => $row['id'],
            'week_day' => $row['week_day'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
        );
        $result[] = $schedule; // Adiciona o horário ao array de resultados
    }
    return $result;
}

function editAssistant($id, $name, $tax_id, $birth_date, $phone_number, $email, $salary, $password, $photo, $active_assistant, $sch1, $sch2, $sch3, $sch4, $sch5){
    global $db;
    $stmt = $db -> prepare ('UPDATE Assistant SET person_name=?, tax_id=?, birth_date=?, phone_number=?, email=?, salary=?, assistant_password=?, photo=?, active_assistant=?, schedule1_id=?, schedule2_id=?, schedule3_id=?, schedule4_id=?, schedule5_id=? WHERE id = ?');
    $stmt -> execute(array($name, $tax_id, $birth_date, $phone_number, $email, $salary, $password, $photo, $active_assistant, $sch1, $sch2, $sch3, $sch4, $sch5, $id));
}

function checkTaxIdRepeatedAssistant($tax_id) {
    global $db;

    if ($tax_id == $_SESSION['tax_idAss']) {
        return false;
    } else {
        // Consulta para verificar a existência do valor 'nif' em todas as tabelas
        $stmt = $db -> prepare('SELECT COUNT(*) AS total FROM 
            (SELECT email FROM Dentist WHERE tax_id = ?
            UNION
            SELECT email FROM Assistant WHERE tax_id = ?
            UNION
            SELECT email FROM Patient WHERE tax_id = ?)');
        $stmt -> execute(array($tax_id,$tax_id,$tax_id));

        $total = 0;
        if ($row = $stmt->fetch()) {
            $total =  $row['total'];
        }
        echo $total;
        if ($total > 0) {
            // O valor 'nif' já existe em pelo menos uma tabela
            return true;
        } else {
            // O valor 'nif' é único em todas as tabelas
            return false;
        }
    }
}

function numberLinesAssistant(){
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM Assistant");
    $stmt->execute();

    $total=0;
    if ($row = $stmt->fetch()) {
        $total =  $row['total'];
    }
    return $total;
}

function createAssistant($id, $name, $tax_id, $birth_date, $phone_number, $email, $salary, $password, $photo, $active_assistant, $sch1, $sch2, $sch3, $sch4, $sch5){
    global $db;
    $stmt = $db -> prepare('INSERT INTO Assistant VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt -> execute(array($id, $name, $tax_id, $birth_date, $phone_number, $email, $salary, $password, $photo, $active_assistant, $sch1, $sch2, $sch3, $sch4, $sch5));
}

function removeAddAssistant(){
    global $db;
    session_start();
    $stmt = $db -> prepare ('UPDATE Assistant SET active_assistant=? WHERE id = ?');
    if ($_SESSION['active_assistant']==0){
        $stmt -> execute(array(1, $_SESSION['idAss']));
    }else{
        $stmt -> execute(array(0, $_SESSION['idAss']));
    }
}

//--------------------------------------------------Paciente-------------------------------------------------
function getAllDataPatient($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM Patient WHERE id = ?');
    $stmt->execute(array($id));

    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    return $patient;
}

function checkTaxIdRepeatedPatient($tax_id) {
    global $db;

    if ($tax_id == $_SESSION['tax_idPat']) {
        return false;
    } else {
        // Consulta para verificar a existência do valor 'nif' em todas as tabelas
        $stmt = $db -> prepare('SELECT COUNT(*) AS total FROM 
            (SELECT email FROM Dentist WHERE tax_id = ?
            UNION
            SELECT email FROM Assistant WHERE tax_id = ?
            UNION
            SELECT email FROM Patient WHERE tax_id = ?)');
        $stmt -> execute(array($tax_id,$tax_id,$tax_id));

        $total = 0;
        if ($row = $stmt->fetch()) {
            $total =  $row['total'];
        }
        echo $total;
        if ($total > 0) {
            // O valor 'nif' já existe em pelo menos uma tabela
            return true;
        } else {
            // O valor 'nif' é único em todas as tabelas
            return false;
        }
    }
}

function editPatient($id, $name, $tax_id, $birth_date, $phone_number, $email){
    global $db;
    $stmt = $db -> prepare ('UPDATE Patient SET person_name=?, tax_id=?, birth_date=?, phone_number=?, email=? WHERE id = ?');
    $stmt -> execute(array($name, $tax_id, $birth_date, $phone_number, $email, $id));
}

function createPacient($name, $tax_id, $birth_date, $phone_number, $email){
    global $db;
    $stmt = $db -> prepare('INSERT INTO Patient (person_name, tax_id, birth_date, phone_number, email) VALUES (?,?,?,?,?)');
    $stmt -> execute(array($name, $tax_id, $birth_date, $phone_number, $email));
}


?>


