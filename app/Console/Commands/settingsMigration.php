<?php

namespace App\Console\Commands;

use App\Models\Settings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ReflectionClass;
use ReflectionProperty;

class settingsMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:migrate-class {class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration fron non migrated files of the given settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settingsClass = $this->argument('class');
        $settingsClass = 'App\\Settings\\' . $settingsClass;

        if(!class_exists($settingsClass)){
            $this->error('Class ' . $settingsClass . ' does not exist');
            return 1;
        }


        $group = $settingsClass::group();

        $properties = $this->getPublicProperties($settingsClass);

        $unmigrated = $this->unMigratedProperties($properties, $group);

        if(empty($unmigrated)){
            $this->error('No Settings to migrate!');
            return 1;
        }

        $content = $this->migratorFileContent($unmigrated, $group);

        $filename = database_path('settings\\') . now()->format('Y_m_d_His') . '_' . \Str::slug($this->argument('class')) . '.php';

        file_put_contents($filename, $content);

        $this->info('Migration file created!');

        $exitCode = Artisan::call('migrate');

        if ($exitCode === 0) {
            $this->info("File '{$filename}' has been created successfully and migrations have been run.");
        } else {
            $this->error( "File '{$filename}' has been created successfully, but there was an issue running migrations. Exit code: {$exitCode}");
        }

        return 1;

    }


    function getPublicProperties($className)
    {
        $reflection = new ReflectionClass($className);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = [];

        foreach ($properties as $property) {
            $result[$property->getName()] = $property->getDefaultValue();
        }

        return $result;
    }


    public function unMigratedProperties($properties, $group)
    {
        return array_filter($properties, function ($value, $property) use ($group) {
           return !Settings::where('name', $property)->where('group', $group)->exists();
        }, ARRAY_FILTER_USE_BOTH);
    }


    function migratorFileContent($properties, $group) {

        $content = "<?php\n\n";
        $content .= "use Spatie\LaravelSettings\Migrations\SettingsMigration;\n\n";
        $content .= "return new class extends SettingsMigration\n{\n";
        $content .= "public function up(): void\n{\n";

        foreach ($properties as $propertyKey => $propertyValue) {
            $content .= "        \$this->migrator->add('$group.$propertyKey', " . var_export($propertyValue, true) . ");\n";
        }

        $content .= "    }\n};\n";

        return $content;
    }

}
