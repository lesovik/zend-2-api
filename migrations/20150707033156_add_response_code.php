<?php

use Phinx\Migration\AbstractMigration;

class AddResponseCode extends AbstractMigration
{
      /**
     * Migrate Up.
     */
    public function up() {
       
    
        $this->execute("
            ALTER TABLE action_log ADD COLUMN response_code integer NOT NULL DEFAULT 200;
        ");
       
        
    }

    /**
     * Migrate Down.
     */
    public function down() {
        $this->execute('ALTER TABLE action_log DROP COLUMN response_code');
    }

}
