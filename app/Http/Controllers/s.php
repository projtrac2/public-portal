<?php

$driver = 'mysql';
$host = 'localhost';
$dbname = 'projtrac_mne';
$db_username = 'root';
$db_password = '';

$dsn = "{$driver}:host={$host}; dbname={$dbname};charset=utf8mb4";

try {
    //create an instance of the PDO class with the required parameters
    $db = new PDO($dsn, $db_username, $db_password);

    //set pdo error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //display success message
    //echo "Connected to the register database";

} catch (PDOException $ex) {
    //display error message
    echo "Connection failed " . $ex->getMessage();
}

$query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' ORDER BY p.projid DESC");
$query_rsProjects->execute();
$totalRows_rsProjects = $query_rsProjects->rowCount();

while ($row_rsProjects = $query_rsProjects->fetch()) {
    $projid = $row_rsProjects['projid'];

    $query_Output = $db->prepare("SELECT * FROM tbl_project_details d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE projid = :projid ");
    $query_Output->execute(array(":projid" => $projid));
    $total_Output = $query_Output->rowCount();

    // $markers = [];
    if ($total_Output > 0) {
        while ($row_rsOutput = $query_Output->fetch()) {
            $output_id = $row_rsOutput['id'];
            $mapping_type = $row_rsOutput['indicator_mapping_type'];

            // $marker = [];
            if ($mapping_type == 1) {
            //     $sql = $db->prepare("SELECT * FROM tbl_project_sites p INNER JOIN tbl_output_disaggregation s ON s.output_site = p.site_id WHERE outputid = :output_id ");
            //     $sql->execute(array(":output_id" => $output_id));
            //     $total_sites = $sql->rowCount();
            //     if ($total_sites > 0) {
            //         while ($row_rsMaps = $sql->fetch()) {
            //             $site_name = $row_rsMaps['site'];
            //             $site_id = $row_rsMaps['site_id'];
            //             $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid AND m.site_id=:site_id");
            //             $query->execute(array(":output_id" => $output_id, ":projid" => $projid, ":site_id" => $site_id));
            //             $row = $query->fetchAll();
            //             $rows = $query->rowCount();
            //             $marker[] = array("site" => $site_name, "markers" => $row);
            //         }
            //     }
            } else {
                $query = $db->prepare("SELECT * FROM tbl_markers m INNER JOIN tbl_project_details d ON d.id = m.opid WHERE d.id = :output_id AND d.projid=:projid");
                $query->execute(array(":output_id" => $output_id, ":projid" => $projid));
                $row = $query->fetchAll();
                $rows = $query->rowCount();
                $marker[] = array("site" => "", "markers" => $row);

                var_dump($row);
            }

            return;
        }
    }
}
