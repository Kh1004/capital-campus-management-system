<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'check:table-structure';
    protected $description = 'Check the structure of the course_user table';

    public function handle()
    {
        $table = 'course_user';
        
        if (!Schema::hasTable($table)) {
            $this->error("Table {$table} does not exist!");
            return 1;
        }

        $columns = Schema::getColumnListing($table);
        
        $this->info("Columns in {$table} table:");
        foreach ($columns as $column) {
            $this->line("- {$column}");
        }
        
        // Check foreign key constraints
        $this->info("\nForeign key constraints:");
        $connection = DB::connection();
        $databaseName = DB::connection()->getDatabaseName();
        
        if ($connection instanceof SQLiteConnection) {
            $foreignKeys = DB::select("PRAGMA foreign_key_list({$table});");
            foreach ($foreignKeys as $fk) {
                $this->line("- {$fk->from} references {$fk->table}.{$fk->to}");
            }
        } else {
            $foreignKeys = DB::select("
                SELECT 
                    COLUMN_NAME, 
                    REFERENCED_TABLE_NAME, 
                    REFERENCED_COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_SCHEMA = '{$databaseName}'
                    AND TABLE_NAME = '{$table}'
                    AND REFERENCED_TABLE_NAME IS NOT NULL;
            ");
            
            foreach ($foreignKeys as $fk) {
                $this->line("- {$fk->COLUMN_NAME} references {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME}");
            }
        }
        
        return 0;
    }
}
