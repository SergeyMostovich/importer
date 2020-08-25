<?php declare(strict_types=1);

use TestTask\AppData;
use TestTask\Import\ActionException;
use TestTask\Import\Csv\DummyDataGenerator;
use TestTask\Import\ImportDecorator;
use TestTask\Import\Reader;
use TestTask\Search\Search;
use TestTask\UserDatabaseConfig;

require_once __DIR__.'/vendor/autoload.php';

$app = new AppData();
try {
    switch ($app->getOptions()->action) {
        case 'search':
            $search = new Search($app);
            break;
        case 'truncate':
            $app->getDatabase()->truncateTable(UserDatabaseConfig::TABLE);
            $app->getLogger()->info(sprintf('Successfully truncate table %s', UserDatabaseConfig::TABLE));

            break;
        case 'createtable':
            $app->getDatabase()->createTable(UserDatabaseConfig::TABLE);
            $app->getLogger()->info(sprintf('Successfully created table %s', UserDatabaseConfig::TABLE));

            break;
        case 'generate':
            switch ($app->getOptions()->type) {
                case 'csv':
                    $dummyData = new DummyDataGenerator($app->getLogger());
                    $path = __DIR__.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.'data.csv';
                    break;
                default:
                    $app->getLogger()->error('Wrong file format');
                    die;
            }
            $dummyData->setUsersCount($app->getOptions()->count)
                ->setPath($path)
                ->build();
            $app->getLogger()->info(
                sprintf('Successfully generated %s users in to %s', $app->getOptions()->count, $path)
            );
            break;
        case 'import':
            $app->getLogger()->info('app started');
            $path = $app->getOptions()->path;
            $app->getLogger()->info(sprintf('Use %s', $path));
            $reader = new Reader($app->getLogger(), $path);
            switch ($app->getOptions()->type) {
                case 'csv':
                    $importer = new \TestTask\Import\Csv\Import($app, $reader);
                    break;
                default:
                    $app->getLogger()->error('Wrong file format');
                    die;
            }

            $import = new ImportDecorator($reader, $importer);
            $import->process();
            break;
        default:
            throw new ActionException('Wrong Action');
    }
}catch (ActionException $e){
    $app->getLogger()->error($e->getMessage());
}