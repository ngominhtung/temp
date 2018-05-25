<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_groups')->insert([
            'id' => ('2'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('3'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('4'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('5'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('6'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('7'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('8'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
        DB::table('company_groups')->insert([
            'id' => ('9'),
            'company_id' => ('co001'),
            'name' => ('secret'),
            'parent_id' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);

    }
    public function runParent_id(){
        DB::table('companies')->insert([
            'id' => ('co001'),
            'name' => ('fpt'),
            'status' => ('1'),
            'created' => ('1/01/2011'),
            'created_id'=>('usB51FxA'),
            'modified' =>('1/01/2011'),
            'modified_id'=>('usB51FxA'),
            'deleted'=>('1/01/2011'),
            'deleted_id'=>('usB51FxA'),
        ]);
    }
}
