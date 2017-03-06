<?php
namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * A migration class. It either upgrades the database schema (moves it to new state)
 * or downgrades it to the previous state.
 */

class VersionInitial extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */   
    public function getDescription()
    {
        $description = 'This is the initial migration which creates the user table.';
        return $description;
    }
    
    /**
     * Upgrades the schema to its newer state.
     * @param Schem $schema
     */
    
   public function up(Schema $schema)
   {
       // Create user table.
       $table = $schema->createTable('users');
       $table->addColumn('id', 'integer', ['autoincrement' => true]);
       $table->addColumn('email', 'string', ['notnull'=>true, 'length'=>128]);
       $table->addColumn('user_name', 'string', ['notnull'=>true, 'length'=>512]);
       $table->addColumn('password', 'string', ['notnull'=>true, 'length'=>256]);
       $table->addColumn('status', 'integer', ['notnull'=>true]);
       $table->addColumn('user_created', 'datetime', ['notnull'=>true]);
       $table->addColumn('user_updated', 'datetime', ['notnull'=>false]);
       $table->addColumn('pwd_created', 'datetime', ['notnull'=>true]);
       $table->addColumn('pwd_updated', 'datetime', ['notnull'=>false]);
       $table->addColumn('pwd_reset_token', 'string', ['notnull'=>false, 'length'=>32]);
       $table->addColumn('pwd_reset_token_creation_date', 'datetime', ['notnull'=>false]);
       $table->setPrimaryKey(['id']);
       $table->addUniqueIndex(['email'], 'email_idx');
       $table->addOption('engine' , 'InnoDB');
       $table->addOption('charset', 'utf8mb4');
       $table->addOption('collate', 'utf8mb4_unicode_ci');
   }
   
   /**
    * Reverts the schema changes.
    * @param Schema $schema
    */
   public function down(Schema $schema)
   {
       $schema->dropTable('users');
   }
}