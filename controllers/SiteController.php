<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Longman\TelegramBot;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {

        Yii::$app->telegram->sendMessage([
            'chat_id' => 165666400,
            'text' => 'this is test',
            'reply_markup' => json_encode([
                'inline_keyboard'=>[
                    [
                        ['text'=>"refresh",'callback_data'=> time()]
                    ]
                ]
            ]),
        ] );
    }

    public function actionSet()
    {
        $bot_api_key  = '464600459:AAHwZtlB9YJDAoC3L9hlb0n7_iETIi6n1nc';
        $bot_username = 'persticker_bot';
        $hook_url = 'https://alistorm.ru/stickers/site/hook';
        try {
            // Create Telegram API object
            $telegram = new TelegramBot\Telegram($bot_api_key, $bot_username);

            // Set webhook
            $result = $telegram->setWebhook($hook_url);
            if ($result->isOk()) {
                echo $result->getDescription();
            }
        } catch (TelegramBot\Exception\TelegramException $e) {
            // log telegram errors
            // echo $e->getMessage();
        }
    }

    public function actionUnset()
    {
        $bot_api_key  = '464600459:AAHwZtlB9YJDAoC3L9hlb0n7_iETIi6n1nc';
        $bot_username = 'persticker_bot';
        try {
            // Create Telegram API object
            $telegram = new TelegramBot\Telegram($bot_api_key, $bot_username);
            // Delete webhook
            $result = $telegram->deleteWebhook();
            if ($result->isOk()) {
                echo $result->getDescription();
            }
        } catch (TelegramBot\Exception\TelegramException $e) {
            echo $e->getMessage();
        }
    }

    public function actionHook()
    {
        $bot_api_key  = '464600459:AAHwZtlB9YJDAoC3L9hlb0n7_iETIi6n1nc';
        $bot_username = 'persticker_bot';
        try {
            // Create Telegram API object
            $telegram = new TelegramBot\Telegram($bot_api_key, $bot_username);

            // Handle telegram webhook request
            $telegram->handle();
        } catch (TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            // log telegram errors
             echo $e->getMessage();
        }
    }


    public function actionCron()
    {
        // Add you bot's API key and name
        $bot_api_key  = '464600459:AAHwZtlB9YJDAoC3L9hlb0n7_iETIi6n1nc';
        $bot_username = 'persticker_bot';
        $commands = [
            '/start',
            "/echo I'm a bot!",
        ];
// Define all IDs of admin users in this array (leave as empty array if not used)
        $admin_users = [
//    123,
        ];
// Define all paths for your custom commands in this array (leave as empty array if not used)
        $commands_paths = [
    __DIR__ . '/../core/Commands/',
        ];

// Enter your MySQL database credentials
//$mysql_credentials = [
//    'host'     => 'localhost',
//    'user'     => 'dbuser',
//    'password' => 'dbpass',
//    'database' => 'dbname',
//];

        try {
            // Create Telegram API object
            $telegram = new TelegramBot\Telegram($bot_api_key, $bot_username);

            // Add commands paths containing your custom commands
            $telegram->addCommandsPaths($commands_paths);

            // Enable admin users
            $telegram->enableAdmins($admin_users);

            // Enable MySQL
            //$telegram->enableMySql($mysql_credentials);

            // Logging (Error, Debug and Raw Updates)
            //TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
            //TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
            //TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");

            // If you are using a custom Monolog instance for logging, use this instead of the above
            //TelegramBot\TelegramLog::initialize($your_external_monolog_instance);

            // Set custom Upload and Download paths
            //$telegram->setDownloadPath(__DIR__ . '/Download');
            //$telegram->setUploadPath(__DIR__ . '/Upload');

            // Here you can set some command specific parameters,
            // e.g. Google geocode/timezone api key for /date command:
            //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);

            // Botan.io integration
            //$telegram->enableBotan('your_botan_token');

            // Requests Limiter (tries to prevent reaching Telegram API limits)
            $telegram->enableLimiter();

            // Run user selected commands
            $telegram->runCommands($commands);

        } catch (TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            echo $e;
            // Log telegram errors
            TelegramBot\TelegramLog::error($e);
        } catch (TelegramBot\Exception\TelegramLogException $e) {
            // Silence is golden!
            // Uncomment this to catch log initialisation errors
            echo $e;
        }
    }

}
