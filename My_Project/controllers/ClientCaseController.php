<?php

include(__DIR__ . "/../db.php");
include(__DIR__ . "/../models/Case.php");

$caseModel = new CaseModel($conn);

$client_id = $_SESSION["id"];


/* Get Client Cases */

$result = $caseModel->getClientCases($client_id);

?>