<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Validator;
use File;
use DB;
use Artisan;

class MainController extends Controller
{
    const PATH_BASE_FILE = 'database/base.php';
    const PATH_TEMP_GROUPKEY_STUB = 'stubs/temp_groupkey.stub';
    const PATH_TEMP_DATABASE_CONFIG_STUB = 'stubs/temp_database.stub';
    const HOST_ANCHOR_DEFINE = '{{host}}';
    const PORT_ANCHOR_DEFINE = '{{port}}';
    const DATABASE_ANCHOR_DEFINE = '{{database}}';
    const USERNAME_ANCHOR_DEFINE = '{{username}}';
    const PASSWORD_ANCHOR_DEFINE = '{{password}}';

    public function checkGroupKey($group_key) {
        if (strlen($group_key) > 12) {
            return 'Invalid group key. Please check again.';
        }

        $cookie = cookie('gk', $group_key, 60*24*30);

        if (!File::exists(storage_path('database'))) {
            File::makeDirectory(storage_path('database'));
        }
        if (!File::exists(storage_path('database/' . $group_key . '.php'))) {
            $new_connection = $this->createGroupKeyFile($group_key, env('DB_HOST'), env('DB_PORT'), $group_key, env('DB_USERNAME'), env('DB_PASSWORD'));
            $this->updateDatabaseConfig($group_key, $new_connection);
        }

        $db = DB::select("SELECT 1 FROM pg_database WHERE datname = '{$group_key}'");
        if (count($db) == 0) {
            DB::statement("CREATE DATABASE {$group_key}");
        }

        $tables = DB::connection('pgsql_' . $group_key)->select("SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_type='BASE TABLE'");
        if (count($tables) == 0) {
            Artisan::call('migrate', ['--database' => 'pgsql_' . $group_key]);
            return redirect('/user/regist')->withCookie($cookie);
        }
        
        return redirect('/login')->withCookie($cookie);
    }

    public function createGroupKeyFile($groupkey, $host, $port, $database, $username, $password) {
        $stub = File::get(storage_path(self::PATH_TEMP_GROUPKEY_STUB));
        $stub = str_replace(self::HOST_ANCHOR_DEFINE, $host, $stub);
        $stub = str_replace(self::PORT_ANCHOR_DEFINE, $port, $stub);
        $stub = str_replace(self::DATABASE_ANCHOR_DEFINE, $database, $stub);
        $stub = str_replace(self::USERNAME_ANCHOR_DEFINE, $username, $stub);
        $stub = str_replace(self::PASSWORD_ANCHOR_DEFINE, $password, $stub);
        $path = storage_path('database') . '/' . $groupkey . '.php';
        File::put($path, $stub);

        return [
            'driver' => 'pgsql',
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer'
        ];
    }

    public function updateDatabaseConfig($group_key, $new_connection) {
        $content_new_file = file_get_contents(storage_path('database') . '/' . $group_key . '.php');
        File::append(storage_path(self::PATH_BASE_FILE), $content_new_file);
        $content_base_file = file_get_contents(storage_path(self::PATH_BASE_FILE));

        $stub = File::get(storage_path(self::PATH_TEMP_DATABASE_CONFIG_STUB));
        $stub = str_replace('{{target}}', $content_base_file, $stub);
        $path = config_path('database.php');
        File::put($path, $stub);

        $connections = config('database.connections');
        $connections['pgsql_' . $group_key] = $new_connection;
        config(['database.connections' => $connections]);
    }
}
