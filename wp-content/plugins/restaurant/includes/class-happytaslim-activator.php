<?php

class Happytaslim_Activator {

    public static function activate() {
        require_once HAPPYTASLIM_PATH . 'includes/database/databaseTable.php';
        $datbasetable = new HappyTaslim_DatabaseTables();
        return $datbasetable::HappyTaslim_add_tables();
    }

}
